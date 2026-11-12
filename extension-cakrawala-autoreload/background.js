/**
 * Cakrawala Auto Reload - Background Service Worker (Manifest V3)
 * Orchestrates automated reload loop, tab management, metrics tracking, and safety checks.
 */

import {
  getConfig,
  getState,
  saveState,
  resetState,
  updateUrlResult,
  addLog,
  validateUrl
} from './storage.js';

const ALARM_NAME = 'cakrawala_reload_timer';

// Initialize extension on install or update
chrome.runtime.onInstalled.addListener(async (details) => {
  if (details.reason === 'install') {
    await resetState();
    await addLog('info', 'Ekstensi Cakrawala Auto Reload berhasil diinstal.');
  }
});

/**
 * Stop any active alarms
 */
async function clearReloadTimer() {
  await chrome.alarms.clear(ALARM_NAME);
}

/**
 * Schedule next reload cycle via Chrome Alarms
 */
async function scheduleNextReload(delaySeconds) {
  await clearReloadTimer();
  const safeDelay = Math.max(2, delaySeconds);
  chrome.alarms.create(ALARM_NAME, {
    delayInMinutes: safeDelay / 60
  });
}

// Alarm listener
chrome.alarms.onAlarm.addListener(async (alarm) => {
  if (alarm.name === ALARM_NAME) {
    await runReloadCycle();
  }
});

/**
 * Ensure a tab is ready for the current target URL
 */
async function getOrCreateTestTab(targetUrl, existingTabId) {
  if (existingTabId) {
    try {
      const tab = await chrome.tabs.get(existingTabId);
      if (tab) {
        // Tab exists, navigate if URL differs
        if (tab.url !== targetUrl) {
          await chrome.tabs.update(existingTabId, { url: targetUrl });
        }
        return tab;
      }
    } catch (e) {
      // Tab was closed, fall through to create new tab
    }
  }

  // Find if already open in another tab
  const allTabs = await chrome.tabs.query({});
  for (const t of allTabs) {
    if (t.url && (t.url === targetUrl || t.url.startsWith(targetUrl.split('?')[0]))) {
      return t;
    }
  }

  // Create new active test tab
  const newTab = await chrome.tabs.create({
    url: targetUrl,
    active: true
  });
  return newTab;
}

/**
 * Wait for a specific tab to finish loading
 */
function waitForTabLoad(tabId, timeoutMs = 25000) {
  return new Promise((resolve, reject) => {
    let timeoutId;

    const listener = (updatedTabId, changeInfo) => {
      if (updatedTabId === tabId && changeInfo.status === 'complete') {
        clearTimeout(timeoutId);
        chrome.tabs.onUpdated.removeListener(listener);
        resolve(true);
      }
    };

    timeoutId = setTimeout(() => {
      chrome.tabs.onUpdated.removeListener(listener);
      resolve(false); // timeout, proceed with caution
    }, timeoutMs);

    chrome.tabs.onUpdated.addListener(listener);
  });
}

/**
 * Main execution step
 */
async function runReloadCycle() {
  const state = await getState();
  const config = await getConfig();

  if (!state.isRunning || state.isPaused) {
    return;
  }

  // Filter enabled URLs
  const activeUrls = (config.urls || []).filter(u => u.enabled);
  if (activeUrls.length === 0) {
    await saveState({
      isRunning: false,
      statusText: 'STOPPED'
    });
    await addLog('warning', 'Pengujian berhenti: Tidak ada URL aktif dalam daftar.');
    await clearReloadTimer();
    return;
  }

  // Check if reached max target
  if (state.currentReloadCount >= config.maxReloads) {
    await saveState({
      isRunning: false,
      statusText: 'COMPLETED',
      endTime: Date.now()
    });
    await addLog('success', `Pengujian selesai! Berhasil mencapai target ${config.maxReloads} reload.`);
    await clearReloadTimer();
    return;
  }

  // Pick target URL based on currentUrlIndex
  const targetIndex = state.currentUrlIndex % activeUrls.length;
  const currentItem = activeUrls[targetIndex];
  const targetUrl = currentItem.url;

  try {
    // 1. Get or create tab
    const tab = await getOrCreateTestTab(targetUrl, state.activeTabId);
    await saveState({ activeTabId: tab.id });

    // 2. Trigger reload
    const isFirstRun = (state.currentReloadCount === 0);
    if (!isFirstRun) {
      await chrome.tabs.reload(tab.id, { bypassCache: true });
    }

    // 3. Wait for page to fully load
    await waitForTabLoad(tab.id, 20000);

    // Give content script a brief moment to render dynamic views
    await new Promise(r => setTimeout(r, 600));

    // 4. Query metrics from content script
    let metrics = null;
    try {
      const response = await chrome.tabs.sendMessage(tab.id, { type: 'GET_METRICS' });
      if (response && response.success) {
        metrics = response.metrics;
      }
    } catch (msgErr) {
      // Content script may need injection fallback
      try {
        await chrome.scripting.executeScript({
          target: { tabId: tab.id },
          files: ['content.js']
        });
        await new Promise(r => setTimeout(r, 400));
        const retryResp = await chrome.tabs.sendMessage(tab.id, { type: 'GET_METRICS' });
        if (retryResp && retryResp.success) {
          metrics = retryResp.metrics;
        }
      } catch (e2) {
        // Still failed, will be handled below
      }
    }

    // 5. Evaluate response & page health
    if (!metrics) {
      const nextFail = state.failureCount + 1;
      await saveState({ failureCount: nextFail });
      await addLog('warning', `Reload #${state.currentReloadCount + 1}: Gagal membaca data halaman (${targetUrl}).`);
    } else if (metrics.isError) {
      const nextFail = state.failureCount + 1;
      await saveState({ failureCount: nextFail });
      await addLog('error', `Error Halaman pada ${targetUrl}: ${metrics.errorMessage}`);

      if (config.autoStopOnError) {
        await saveState({
          isRunning: false,
          statusText: 'ERROR',
          endTime: Date.now()
        });
        await clearReloadTimer();
        await addLog('error', 'Pengujian dihentikan otomatis karena terdeteksi error halaman.');
        return;
      }
    } else {
      // Success
      const nextSuccess = state.successCount + 1;
      const nextCount = state.currentReloadCount + 1;

      // Update URL result tracking
      const updatedResult = await updateUrlResult(targetUrl, {
        title: metrics.title || currentItem.title,
        views: metrics.views,
        incrementReload: true
      });

      const deltaStr = updatedResult.lastDelta >= 0 ? `+${updatedResult.lastDelta}` : `${updatedResult.lastDelta}`;
      const viewStr = metrics.views !== null ? `${metrics.views} views (${deltaStr})` : 'views tidak terdeteksi';

      await addLog(
        'success',
        `Reload #${nextCount}/${config.maxReloads} sukses | ${viewStr} | ${metrics.title.substring(0, 32)}...`
      );

      await saveState({
        currentReloadCount: nextCount,
        successCount: nextSuccess,
        currentUrlIndex: (targetIndex + 1) % activeUrls.length
      });
    }

    // 6. Check if target count completed after this cycle
    const latestState = await getState();
    if (latestState.currentReloadCount >= config.maxReloads) {
      await saveState({
        isRunning: false,
        statusText: 'COMPLETED',
        endTime: Date.now()
      });
      await addLog('success', `Pengujian selesai! Seluruh ${config.maxReloads} reload berhasil dieksekusi.`);
      await clearReloadTimer();
      return;
    }

    // 7. Schedule next interval
    if (latestState.isRunning && !latestState.isPaused) {
      await scheduleNextReload(config.interval);
    }
  } catch (err) {
    const nextFail = (state.failureCount || 0) + 1;
    await saveState({ failureCount: nextFail });
    await addLog('error', `Terjadi error eksekusi: ${err.message || err}`);

    if (config.autoStopOnError) {
      await saveState({
        isRunning: false,
        statusText: 'ERROR',
        endTime: Date.now()
      });
      await clearReloadTimer();
    } else {
      await scheduleNextReload(config.interval);
    }
  }
}

// Runtime message listener for Popup UI commands
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
  (async () => {
    switch (message.type) {
      case 'START_TEST': {
        const config = await getConfig();
        const activeUrls = (config.urls || []).filter(u => u.enabled);

        if (activeUrls.length === 0) {
          sendResponse({ success: false, error: 'Tidak ada URL aktif untuk diuji. Silakan centang minimal 1 URL.' });
          return;
        }

        await clearReloadTimer();
        await saveState({
          isRunning: true,
          isPaused: false,
          currentUrlIndex: 0,
          currentReloadCount: 0,
          successCount: 0,
          failureCount: 0,
          startTime: Date.now(),
          endTime: null,
          statusText: 'RUNNING'
        });

        await addLog('info', `Memulai pengujian otomatis: Target ${config.maxReloads} reload (Interval ${config.interval}s).`);
        
        // Execute immediately
        runReloadCycle();
        sendResponse({ success: true });
        break;
      }

      case 'PAUSE_TEST': {
        await clearReloadTimer();
        await saveState({
          isPaused: true,
          statusText: 'PAUSED'
        });
        await addLog('warning', 'Pengujian dijeda sementara oleh pengguna.');
        sendResponse({ success: true });
        break;
      }

      case 'RESUME_TEST': {
        const config = await getConfig();
        await saveState({
          isPaused: false,
          statusText: 'RUNNING'
        });
        await addLog('info', 'Melanjutkan pengujian otomatis...');
        runReloadCycle();
        sendResponse({ success: true });
        break;
      }

      case 'STOP_TEST': {
        await clearReloadTimer();
        await saveState({
          isRunning: false,
          isPaused: false,
          statusText: 'STOPPED',
          endTime: Date.now()
        });
        await addLog('info', 'Pengujian dihentikan oleh pengguna.');
        sendResponse({ success: true });
        break;
      }

      case 'CONTENT_LOADED': {
        // Can be used for live telemetry
        sendResponse({ received: true });
        break;
      }

      default:
        sendResponse({ success: false, error: 'Unknown message type' });
    }
  })();

  return true; // Keep channel open for async response
});


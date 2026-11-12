/**
 * Cakrawala Auto Reload - Popup Controller
 * Manages UI interactions, live telemetry, URL management, and state sync.
 */

import {
  getConfig,
  saveConfig,
  getState,
  saveState,
  getResults,
  clearResults,
  getLogs,
  clearLogs,
  validateUrl,
  addLog
} from './storage.js';

let timerInterval = null;

// DOM Elements
const bodyEl = document.body;
const themeToggleBtn = document.getElementById('themeToggleBtn');
const themeIconSun = document.getElementById('themeIconSun');
const themeIconMoon = document.getElementById('themeIconMoon');

const statusPulse = document.getElementById('statusPulse');
const statusLabel = document.getElementById('statusLabel');
const durationDisplay = document.getElementById('durationDisplay');
const progressText = document.getElementById('progressText');
const progressBar = document.getElementById('progressBar');

const startBtn = document.getElementById('startBtn');
const pauseBtn = document.getElementById('pauseBtn');
const stopBtn = document.getElementById('stopBtn');

const statTotalUrls = document.getElementById('statTotalUrls');
const statSuccessCount = document.getElementById('statSuccessCount');
const statFailureCount = document.getElementById('statFailureCount');
const statTargetTotal = document.getElementById('statTargetTotal');

const urlInput = document.getElementById('urlInput');
const addUrlBtn = document.getElementById('addUrlBtn');
const grabCurrentTabBtn = document.getElementById('grabCurrentTabBtn');
const urlFeedback = document.getElementById('urlFeedback');
const urlListContainer = document.getElementById('urlListContainer');
const activeUrlCount = document.getElementById('activeUrlCount');

const intervalInput = document.getElementById('intervalInput');
const maxReloadsInput = document.getElementById('maxReloadsInput');
const autoStopOnErrorCheckbox = document.getElementById('autoStopOnErrorCheckbox');

const viewResultsContainer = document.getElementById('viewResultsContainer');
const resetResultsBtn = document.getElementById('resetResultsBtn');

const logListContainer = document.getElementById('logListContainer');
const clearLogsBtn = document.getElementById('clearLogsBtn');

// Navigation Tabs
const navTabs = document.querySelectorAll('.nav-tab');
const tabPanes = document.querySelectorAll('.tab-pane');

/**
 * Format duration in seconds to MM:SS or HH:MM:SS
 */
function formatDuration(ms) {
  if (!ms || ms < 0) return '00:00';
  const totalSeconds = Math.floor(ms / 1000);
  const hours = Math.floor(totalSeconds / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const seconds = totalSeconds % 60;

  const pad = (n) => String(n).padStart(2, '0');
  if (hours > 0) {
    return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
  }
  return `${pad(minutes)}:${pad(seconds)}`;
}

/**
 * Update stopwatch display
 */
function updateTimer(startTime, isRunning, endTime) {
  if (!startTime) {
    durationDisplay.textContent = '00:00';
    return;
  }

  const now = isRunning ? Date.now() : (endTime || Date.now());
  const elapsed = Math.max(0, now - startTime);
  durationDisplay.textContent = formatDuration(elapsed);
}

/**
 * Switch Theme
 */
function applyTheme(theme) {
  if (theme === 'light') {
    bodyEl.classList.remove('theme-dark');
    bodyEl.classList.add('theme-light');
    themeIconSun.classList.add('d-none');
    themeIconMoon.classList.remove('d-none');
  } else {
    bodyEl.classList.remove('theme-light');
    bodyEl.classList.add('theme-dark');
    themeIconMoon.classList.add('d-none');
    themeIconSun.classList.remove('d-none');
  }
}

/**
 * Render Status & Metrics
 */
function renderState(state, config) {
  // Status label & Pulse dot
  const status = (state.statusText || 'IDLE').toUpperCase();
  statusLabel.textContent = status;

  statusPulse.className = 'pulse-dot';
  if (status === 'RUNNING') statusPulse.classList.add('running');
  else if (status === 'PAUSED') statusPulse.classList.add('paused');
  else if (status === 'COMPLETED') statusPulse.classList.add('completed');
  else if (status === 'ERROR') statusPulse.classList.add('error');
  else if (status === 'STOPPED') statusPulse.classList.add('stopped');
  else statusPulse.classList.add('idle');

  // Progress Bar
  const totalTarget = config.maxReloads || 10;
  const currentCount = state.currentReloadCount || 0;
  const percent = totalTarget > 0 ? Math.min(100, Math.round((currentCount / totalTarget) * 100)) : 0;

  progressText.textContent = `${currentCount} / ${totalTarget} (${percent}%)`;
  progressBar.style.width = `${percent}%`;

  // Stats Grid
  const totalActive = (config.urls || []).filter(u => u.enabled).length;
  statTotalUrls.textContent = totalActive;
  statSuccessCount.textContent = state.successCount || 0;
  statFailureCount.textContent = state.failureCount || 0;
  statTargetTotal.textContent = totalTarget;

  // Buttons State
  if (state.isRunning) {
    startBtn.disabled = true;
    startBtn.innerHTML = `
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="6 3 20 12 6 21 6 3"/>
      </svg>
      <span>Sedang Berjalan...</span>
    `;
    pauseBtn.disabled = false;
    stopBtn.disabled = false;

    if (state.isPaused) {
      pauseBtn.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="5 3 19 12 5 21 5 3"/>
        </svg>
      `;
      pauseBtn.title = 'Lanjutkan Pengujian';
    } else {
      pauseBtn.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
        </svg>
      `;
      pauseBtn.title = 'Jeda Pengujian';
    }
  } else {
    startBtn.disabled = false;
    startBtn.innerHTML = `
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="6 3 20 12 6 21 6 3"/>
      </svg>
      <span>Mulai Pengujian</span>
    `;
    pauseBtn.disabled = true;
    stopBtn.disabled = true;
  }

  // Timer loop
  if (timerInterval) clearInterval(timerInterval);
  updateTimer(state.startTime, state.isRunning, state.endTime);
  if (state.isRunning && !state.isPaused) {
    timerInterval = setInterval(() => {
      updateTimer(state.startTime, true, null);
    }, 1000);
  }
}

/**
 * Render URL list
 */
function renderUrls(urls) {
  urlListContainer.innerHTML = '';
  const activeCount = (urls || []).filter(u => u.enabled).length;
  activeUrlCount.textContent = activeCount;

  if (!urls || urls.length === 0) {
    urlListContainer.innerHTML = `
      <div class="empty-state">
        Belum ada URL artikel. Masukkan URL artikel Cakrawala di atas.
      </div>
    `;
    return;
  }

  urls.forEach((item) => {
    const itemEl = document.createElement('div');
    itemEl.className = 'url-item';

    const cleanTitle = item.title || 'Artikel Cakrawala';

    itemEl.innerHTML = `
      <div class="url-item-left">
        <label class="toggle-switch">
          <input type="checkbox" data-id="${item.id}" class="url-checkbox" ${item.enabled ? 'checked' : ''}>
          <span class="toggle-slider"></span>
        </label>
        <div class="url-item-meta">
          <span class="url-item-title" title="${cleanTitle}">${cleanTitle}</span>
          <a href="${item.url}" target="_blank" rel="noopener" class="url-item-link" title="${item.url}">
            ${item.url}
          </a>
        </div>
      </div>
      <button class="btn-delete-url" data-id="${item.id}" title="Hapus URL dari daftar">
        <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14H6L5 6"/>
          <path d="M10 11v6"/><path d="M14 11v6"/>
        </svg>
      </button>
    `;

    urlListContainer.appendChild(itemEl);
  });
}

/**
 * Render View Counter Results
 */
function renderResults(results) {
  viewResultsContainer.innerHTML = '';
  const entries = Object.entries(results || {});

  if (entries.length === 0) {
    viewResultsContainer.innerHTML = `
      <div class="empty-state">
        Belum ada hasil pengujian view counter. Mulai pengujian untuk melihat perbandingan angka views.
      </div>
    `;
    return;
  }

  entries.forEach(([url, item]) => {
    const card = document.createElement('div');
    card.className = 'view-result-card';

    const delta = item.lastDelta || 0;
    const deltaBadgeClass = delta > 0 ? 'pos' : 'neutral';
    const deltaSign = delta > 0 ? `+${delta}` : `${delta}`;

    card.innerHTML = `
      <div class="vrc-title" title="${item.title || url}">${item.title || 'Artikel'}</div>
      <div class="vrc-url" title="${url}">${url}</div>
      <div class="vrc-metrics-row">
        <div class="vrc-metric-item">
          <span class="vrc-label">Views Awal</span>
          <span class="vrc-val">${item.initialViews !== null ? item.initialViews : '-'}</span>
        </div>
        <div class="vrc-metric-item">
          <span class="vrc-label">Views Akhir</span>
          <span class="vrc-val">${item.currentViews !== null ? item.currentViews : '-'}</span>
        </div>
        <div class="vrc-metric-item">
          <span class="vrc-label">Perubahan</span>
          <span class="vrc-delta-badge ${deltaBadgeClass}">${deltaSign} Views</span>
        </div>
        <div class="vrc-metric-item">
          <span class="vrc-label">Reloads</span>
          <span class="vrc-val">${item.reloadCount || 0}x</span>
        </div>
      </div>
    `;

    viewResultsContainer.appendChild(card);
  });
}

/**
 * Render Activity Logs
 */
function renderLogs(logs) {
  logListContainer.innerHTML = '';

  if (!logs || logs.length === 0) {
    logListContainer.innerHTML = `
      <div class="empty-state">
        Belum ada catatan aktivitas.
      </div>
    `;
    return;
  }

  logs.forEach(log => {
    const item = document.createElement('div');
    item.className = `log-item ${log.type || 'info'}`;
    item.innerHTML = `
      <span class="log-time">[${log.timestamp}]</span>
      <span class="log-msg">${log.message}</span>
    `;
    logListContainer.appendChild(item);
  });
}

/**
 * Feedback helper for URL input
 */
function showUrlFeedback(msg, isSuccess = false) {
  urlFeedback.textContent = msg;
  urlFeedback.className = `input-feedback ${isSuccess ? 'success' : 'error'}`;
  urlFeedback.classList.remove('d-none');
  setTimeout(() => {
    urlFeedback.classList.add('d-none');
  }, 4500);
}

/**
 * Main Initialization
 */
async function init() {
  const config = await getConfig();
  const state = await getState();
  const results = await getResults();
  const logs = await getLogs();

  // Apply theme
  applyTheme(config.theme || 'dark');

  // Inputs
  intervalInput.value = config.interval || 5;
  maxReloadsInput.value = config.maxReloads || 10;
  autoStopOnErrorCheckbox.checked = config.autoStopOnError !== false;

  // Render UI
  renderState(state, config);
  renderUrls(config.urls || []);
  renderResults(results);
  renderLogs(logs);

  // Watch for storage changes from background worker
  chrome.storage.onChanged.addListener(async (changes) => {
    const latestConfig = await getConfig();
    const latestState = await getState();
    const latestResults = await getResults();
    const latestLogs = await getLogs();

    if (changes.config) {
      renderUrls(latestConfig.urls);
    }
    if (changes.state) {
      renderState(latestState, latestConfig);
    }
    if (changes.results) {
      renderResults(latestResults);
    }
    if (changes.logs) {
      renderLogs(latestLogs);
    }
  });

  // Theme Toggle Event
  themeToggleBtn.addEventListener('click', async () => {
    const curConfig = await getConfig();
    const nextTheme = (curConfig.theme === 'light') ? 'dark' : 'light';
    await saveConfig({ theme: nextTheme });
    applyTheme(nextTheme);
  });

  // Navigation Tabs Event
  navTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const targetId = tab.getAttribute('data-tab');
      navTabs.forEach(t => t.classList.remove('active'));
      tabPanes.forEach(p => p.classList.remove('active'));
      tab.classList.add('active');
      const pane = document.getElementById(targetId);
      if (pane) pane.classList.add('active');
    });
  });

  // Add URL Event
  addUrlBtn.addEventListener('click', async () => {
    const raw = urlInput.value.trim();
    if (!raw) {
      showUrlFeedback('Silakan ketik atau tempelkan URL artikel.');
      return;
    }

    const val = validateUrl(raw);
    if (!val.valid) {
      showUrlFeedback(val.error);
      return;
    }

    const curConfig = await getConfig();
    const existing = curConfig.urls || [];

    if (existing.some(item => item.url === val.url)) {
      showUrlFeedback('URL ini sudah ada di dalam antrean.');
      return;
    }

    const newItem = {
      id: 'url_' + Date.now().toString(36),
      url: val.url,
      title: 'Artikel ' + (existing.length + 1),
      enabled: true
    };

    const updated = [...existing, newItem];
    await saveConfig({ urls: updated });
    await addLog('info', `URL ditambahkan: ${val.url}`);
    urlInput.value = '';
    showUrlFeedback('URL berhasil ditambahkan ke antrean!', true);
    renderUrls(updated);
  });

  // Grab Current Tab Event
  grabCurrentTabBtn.addEventListener('click', async () => {
    try {
      const [activeTab] = await chrome.tabs.query({ active: true, currentWindow: true });
      if (activeTab && activeTab.url) {
        urlInput.value = activeTab.url;
        showUrlFeedback('URL tab aktif berhasil diambil.', true);
      } else {
        showUrlFeedback('Gagal mendeteksi URL dari tab yang aktif.');
      }
    } catch (e) {
      showUrlFeedback('Error mengakses tab: ' + e.message);
    }
  });

  // URL list interactions (Checkbox toggle & Delete)
  urlListContainer.addEventListener('click', async (e) => {
    const target = e.target.closest('button, input');
    if (!target) return;

    const id = target.getAttribute('data-id');
    const curConfig = await getConfig();
    let urls = curConfig.urls || [];

    if (target.classList.contains('url-checkbox')) {
      const isChecked = target.checked;
      urls = urls.map(u => u.id === id ? { ...u, enabled: isChecked } : u);
      await saveConfig({ urls });
      renderUrls(urls);
      const curState = await getState();
      renderState(curState, { ...curConfig, urls });
    } else if (target.classList.contains('btn-delete-url') || target.closest('.btn-delete-url')) {
      const btn = target.classList.contains('btn-delete-url') ? target : target.closest('.btn-delete-url');
      const deleteId = btn.getAttribute('data-id');
      const removed = urls.find(u => u.id === deleteId);
      urls = urls.filter(u => u.id !== deleteId);
      await saveConfig({ urls });
      if (removed) {
        await addLog('info', `URL dihapus: ${removed.url}`);
      }
      renderUrls(urls);
      const curState = await getState();
      renderState(curState, { ...curConfig, urls });
    }
  });

  // Settings change events
  intervalInput.addEventListener('change', async () => {
    let val = parseInt(intervalInput.value, 10);
    if (isNaN(val) || val < 2) val = 2;
    if (val > 120) val = 120;
    intervalInput.value = val;
    await saveConfig({ interval: val });
  });

  maxReloadsInput.addEventListener('change', async () => {
    let val = parseInt(maxReloadsInput.value, 10);
    if (isNaN(val) || val < 1) val = 1;
    if (val > 100) val = 100;
    maxReloadsInput.value = val;
    await saveConfig({ maxReloads: val });
    const curState = await getState();
    const curConfig = await getConfig();
    renderState(curState, curConfig);
  });

  autoStopOnErrorCheckbox.addEventListener('change', async () => {
    await saveConfig({ autoStopOnError: autoStopOnErrorCheckbox.checked });
  });

  // Master Action Buttons (Start, Pause, Stop)
  startBtn.addEventListener('click', () => {
    chrome.runtime.sendMessage({ type: 'START_TEST' }, (resp) => {
      if (resp && !resp.success && resp.error) {
        showUrlFeedback(resp.error);
      }
    });
  });

  pauseBtn.addEventListener('click', async () => {
    const curState = await getState();
    if (curState.isPaused) {
      chrome.runtime.sendMessage({ type: 'RESUME_TEST' });
    } else {
      chrome.runtime.sendMessage({ type: 'PAUSE_TEST' });
    }
  });

  stopBtn.addEventListener('click', () => {
    chrome.runtime.sendMessage({ type: 'STOP_TEST' });
  });

  // Reset Results Button
  resetResultsBtn.addEventListener('click', async () => {
    await clearResults();
    await addLog('info', 'Data pengujian view counter dibersihkan.');
    renderResults({});
  });

  // Clear Logs Button
  clearLogsBtn.addEventListener('click', async () => {
    await clearLogs();
    renderLogs([]);
  });
}

// Start
document.addEventListener('DOMContentLoaded', init);


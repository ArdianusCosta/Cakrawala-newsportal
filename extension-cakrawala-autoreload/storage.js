/**
 * Cakrawala Auto Reload - Storage Module
 * Manages configuration, persistent state, metrics, and activity logs.
 */

export const ALLOWED_HOSTS = [
  'cakrawalaonline.com',
  'www.cakrawalaonline.com',
  'localhost',
  '127.0.0.1'
];

export const DEFAULT_CONFIG = {
  urls: [
    {
      id: 'demo-1',
      url: 'https://cakrawalaonline.com/news-details.php?nid=597',
      title: 'Artikel Default',
      enabled: true
    }
  ],
  interval: 5,        // in seconds (min 2, max 120)
  maxReloads: 10,     // total target cycles
  autoStopOnError: true,
  theme: 'dark'
};

export const DEFAULT_STATE = {
  isRunning: false,
  isPaused: false,
  currentUrlIndex: 0,
  currentReloadCount: 0,
  successCount: 0,
  failureCount: 0,
  startTime: null,
  endTime: null,
  activeTabId: null,
  statusText: 'IDLE' // IDLE, RUNNING, PAUSED, STOPPED, COMPLETED, ERROR
};

/**
 * Validates whether a URL is formatted properly and points to an authorized domain.
 */
export function validateUrl(rawUrl) {
  try {
    const parsed = new URL(rawUrl.trim());
    if (parsed.protocol !== 'http:' && parsed.protocol !== 'https:') {
      return { valid: false, error: 'Protokol harus http:// atau https://' };
    }
    const hostname = parsed.hostname.toLowerCase();
    const isAllowed = ALLOWED_HOSTS.some(host => hostname === host || hostname.endsWith('.' + host));
    if (!isAllowed) {
      return {
        valid: false,
        error: `Domain "${hostname}" tidak diizinkan. Hanya domain Cakrawala atau localhost staging.`
      };
    }
    return { valid: true, url: parsed.href };
  } catch (e) {
    return { valid: false, error: 'Format URL tidak valid.' };
  }
}

/**
 * Get stored config merged with defaults
 */
export async function getConfig() {
  const result = await chrome.storage.local.get(['config']);
  return { ...DEFAULT_CONFIG, ...(result.config || {}) };
}

/**
 * Save updated config
 */
export async function saveConfig(updates) {
  const current = await getConfig();
  const merged = { ...current, ...updates };
  await chrome.storage.local.set({ config: merged });
  return merged;
}

/**
 * Get current running state
 */
export async function getState() {
  const result = await chrome.storage.local.get(['state']);
  return { ...DEFAULT_STATE, ...(result.state || {}) };
}

/**
 * Save updated running state
 */
export async function saveState(updates) {
  const current = await getState();
  const merged = { ...current, ...updates };
  await chrome.storage.local.set({ state: merged });
  return merged;
}

/**
 * Reset state to IDLE
 */
export async function resetState() {
  await chrome.storage.local.set({ state: DEFAULT_STATE });
  return DEFAULT_STATE;
}

/**
 * Get view counter test results
 * Result map: { [url]: { initialViews, currentViews, lastDelta, reloadCount, lastUpdated, title } }
 */
export async function getResults() {
  const result = await chrome.storage.local.get(['results']);
  return result.results || {};
}

/**
 * Update test result for a specific URL
 */
export async function updateUrlResult(url, data) {
  const allResults = await getResults();
  const prev = allResults[url] || {
    initialViews: null,
    currentViews: null,
    lastDelta: 0,
    reloadCount: 0,
    title: ''
  };

  const initialViews = prev.initialViews === null
    ? (typeof data.views === 'number' ? data.views : 0)
    : prev.initialViews;

  const currentViews = typeof data.views === 'number' ? data.views : prev.currentViews;
  const delta = (currentViews !== null && initialViews !== null) ? (currentViews - initialViews) : 0;

  allResults[url] = {
    ...prev,
    ...data,
    initialViews,
    currentViews,
    lastDelta: delta,
    reloadCount: (prev.reloadCount || 0) + (data.incrementReload ? 1 : 0),
    lastUpdated: Date.now()
  };

  await chrome.storage.local.set({ results: allResults });
  return allResults[url];
}

/**
 * Clear test results
 */
export async function clearResults() {
  await chrome.storage.local.set({ results: {} });
}

/**
 * Get activity logs (max 100 entries)
 */
export async function getLogs() {
  const result = await chrome.storage.local.get(['logs']);
  return result.logs || [];
}

/**
 * Append an activity log
 */
export async function addLog(type, message) {
  const logs = await getLogs();
  const newEntry = {
    id: Date.now().toString(36) + Math.random().toString(36).substr(2, 4),
    timestamp: new Date().toLocaleTimeString('id-ID', { hour12: false }),
    type, // 'info', 'success', 'warning', 'error'
    message
  };
  const trimmed = [newEntry, ...logs].slice(0, 80);
  await chrome.storage.local.set({ logs: trimmed });
  return newEntry;
}

/**
 * Clear all logs
 */
export async function clearLogs() {
  await chrome.storage.local.set({ logs: [] });
}


/**
 * Cakrawala Auto Reload - Content Script
 * Injected into Cakrawala Online article pages to inspect view counter and health status.
 */

(() => {
  // Prevent duplicate execution if injected multiple times
  if (window.__cakrawalaContentScriptLoaded) return;
  window.__cakrawalaContentScriptLoaded = true;

  /**
   * Parse numeric view count from article text or elements
   */
  function extractViewCount() {
    // 1. Primary check: Card footer or specific meta text
    const selectors = [
      '.card-footer',
      '.text-muted',
      '.article-meta',
      'p.text-muted',
      '.card-body'
    ];

    for (const sel of selectors) {
      const els = document.querySelectorAll(sel);
      for (const el of els) {
        const text = el.innerText || el.textContent || '';
        // Match "Dilihat: 15 kali" or "Dilihat: 15" or "15 views"
        const matchDilihat = text.match(/Dilihat:\s*([\d.,]+)\s*(kali|views)?/i);
        if (matchDilihat && matchDilihat[1]) {
          const rawNum = matchDilihat[1].replace(/[.,\s]/g, '');
          const val = parseInt(rawNum, 10);
          if (!isNaN(val)) return val;
        }

        const matchViews = text.match(/([\d.,]+)\s*views/i);
        if (matchViews && matchViews[1]) {
          const rawNum = matchViews[1].replace(/[.,\s]/g, '');
          const val = parseInt(rawNum, 10);
          if (!isNaN(val)) return val;
        }
      }
    }

    // 2. Fallback: Search the entire body text
    const bodyText = document.body ? document.body.innerText : '';
    const bodyMatch = bodyText.match(/Dilihat:\s*([\d.,]+)\s*kali/i) || bodyText.match(/([\d.,]+)\s*views/i);
    if (bodyMatch && bodyMatch[1]) {
      const rawNum = bodyMatch[1].replace(/[.,\s]/g, '');
      const val = parseInt(rawNum, 10);
      if (!isNaN(val)) return val;
    }

    return null;
  }

  /**
   * Extract clean article title
   */
  function extractTitle() {
    const titleEl = document.querySelector('h2.card-title') ||
                    document.querySelector('h1.card-title') ||
                    document.querySelector('h1') ||
                    document.querySelector('.card-title');
    if (titleEl && titleEl.innerText.trim()) {
      return titleEl.innerText.trim();
    }
    return document.title ? document.title.split('-')[0].trim() : 'Tanpa Judul';
  }

  /**
   * Check if page is in an error state
   */
  function detectPageError() {
    const pageTitle = (document.title || '').toLowerCase();
    const bodyText = (document.body ? document.body.innerText : '').toLowerCase();

    // Check for HTTP errors or PHP fatal crashes
    const errorKeywords = [
      '404 not found',
      '500 internal server error',
      'koneksi gagal',
      'db connection failed',
      'fatal error',
      'parse error',
      'uncaught runtimeexception',
      'uncaught mysqli_sql_exception',
      'sql error'
    ];

    for (const kw of errorKeywords) {
      if (pageTitle.includes(kw) || bodyText.includes(kw)) {
        return { isError: true, reason: `Terdeteksi error halaman: "${kw}"` };
      }
    }

    if (!document.body || document.body.children.length === 0 || document.body.innerText.trim().length < 20) {
      return { isError: true, reason: 'Halaman kosong atau gagal dimuat (blank page).' };
    }

    return { isError: false, reason: null };
  }

  /**
   * Gather full metrics payload
   */
  function getPageMetrics() {
    const errorCheck = detectPageError();
    const views = extractViewCount();
    const title = extractTitle();

    return {
      url: window.location.href,
      title,
      views,
      isError: errorCheck.isError,
      errorMessage: errorCheck.reason,
      timestamp: Date.now()
    };
  }

  // Listen for queries from background service worker or popup
  chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
    if (message.type === 'GET_METRICS') {
      const metrics = getPageMetrics();
      sendResponse({ success: true, metrics });
    }
    return true; // Keep message channel open for async response
  });

  // Automatically broadcast status when content script initializes
  try {
    const metrics = getPageMetrics();
    chrome.runtime.sendMessage({
      type: 'CONTENT_LOADED',
      metrics
    }).catch(() => {
      // Background worker may be inactive, ignore
    });
  } catch (e) {
    // Ignore context invalidated
  }
})();


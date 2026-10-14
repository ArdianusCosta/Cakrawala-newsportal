/**
 * Smart Image Framing for Cakrawala Online
 * Otomatis mendeteksi rasio foto (Landscape vs Portrait)
 * Menyesuaikan focal point foto secara presisi agar kepala/wajah tidak terpotong.
 */
(function() {
  'use strict';

  function applySmartFraming(img) {
    if (!img) return;

    // Helper untuk mengecek dimensi asli gambar
    function updateFocalPoint() {
      var w = img.naturalWidth;
      var h = img.naturalHeight;
      if (!w || !h) return;

      var ratio = h / w;

      if (ratio >= 1.25) {
        // Foto Vertikal / Portrait Tinggi (khas kamera HP/WhatsApp)
        // Fokuskan ke 28% dari atas (area wajah & kepala manusia)
        img.style.setProperty('object-position', 'center 28%', 'important');
      } else if (ratio >= 1.0) {
        // Foto Persegi / Sedikit Vertikal (1:1 hingga 4:5)
        img.style.setProperty('object-position', 'center 35%', 'important');
      } else if (ratio <= 0.6) {
        // Foto Sangat Melebar / Panorama
        img.style.setProperty('object-position', 'center center', 'important');
      } else {
        // Foto Landscape Standar (4:3 atau 16:9)
        img.style.setProperty('object-position', 'center center', 'important');
      }
    }

    if (img.complete && img.naturalWidth > 0) {
      updateFocalPoint();
    } else {
      img.addEventListener('load', updateFocalPoint, { once: true });
    }
  }

  function initSmartFraming() {
    var selectors = [
      '.article-thumb-img',
      '.headline-img',
      '.headline img',
      '#headlineCarousel img',
      '.thumb-landscape img',
      '.card-img-top',
      '.grid-card img'
    ].join(', ');

    var images = document.querySelectorAll(selectors);
    for (var i = 0; i < images.length; i++) {
      applySmartFraming(images[i]);
    }
  }

  // Jalankan saat DOM siap
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSmartFraming);
  } else {
    initSmartFraming();
  }

  // Dukungan untuk Tab Bootstrap (Terbaru / Terpopuler) saat tab berganti
  if (typeof window.jQuery !== 'undefined') {
    window.jQuery('a[data-toggle="tab"], button[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
      initSmartFraming();
    });
  }

  // Dukungan Carousel saat slide berganti
  document.addEventListener('slide.bs.carousel', function() {
    setTimeout(initSmartFraming, 100);
  });
})();

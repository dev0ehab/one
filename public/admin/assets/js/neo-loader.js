/**
 * Page loader: straight rise, then fade out.
 */
(function (window, document) {
  'use strict';

  var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var DURATION_MS = 1100;

  function finishLoader($) {
    var $html = $('html');
    var $loader = $('#neo-page-loader');
    $html.removeClass('loading').addClass('loaded');
    $loader.addClass('is-exiting');
    setTimeout(function () {
      $loader.fadeOut(280, function () {
        $loader.remove();
      });
    }, 280);
  }

  function init() {
    var loaderEl = document.getElementById('neo-page-loader');
    if (!loaderEl) {
      return;
    }

    if (prefersReducedMotion) {
      loaderEl.classList.add('is-active', 'is-simple');
    } else {
      loaderEl.classList.add('is-active');
    }

    setTimeout(function () {
      if (window.jQuery) {
        finishLoader(window.jQuery);
      } else {
        loaderEl.classList.add('is-exiting');
        setTimeout(function () {
          loaderEl.style.display = 'none';
        }, 320);
      }
    }, prefersReducedMotion ? 400 : DURATION_MS);
  }

  window.neoPageLoader = { init: init };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})(window, document);

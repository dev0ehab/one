/**
 * Premium Admin UI — micro-interactions and global defaults
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    initPageEnter();
    initSidebarScrollActive();
    initNeoBackdrop();
    initButtonLoading();
    initToastrDefaults();
    initSweetAlertDefaults();
  });

  function initPageEnter() {
    var body = document.getElementById('content_body') || document.body;
    if (body && body.classList.contains('admin-neotech')) {
      body.classList.add('neo-page-enter');
    }
  }

  function initSidebarScrollActive() {
    var active = document.querySelector('.main-menu .navigation li.active');
    if (active && typeof active.scrollIntoView === 'function') {
      setTimeout(function () {
        active.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      }, 400);
    }
  }

  function initNeoBackdrop() {
    var backdrop = document.getElementById('neo-backdrop');
    if (!backdrop) {
      return;
    }

    backdrop.addEventListener('click', function () {
      var toggle = document.querySelector('.menu-toggle, .nav-menu-main');
      if (toggle) {
        toggle.click();
      }
    });
  }

  function initButtonLoading() {
    if (typeof jQuery === 'undefined') {
      return;
    }

    jQuery(document).on('click', '.neo-btn, .submit_button', function () {
      var btn = jQuery(this);
      if (btn.prop('disabled') || btn.hasClass('is-loading')) {
        return;
      }
      btn.data('neo-original-html', btn.html());
    });

    jQuery(document).ajaxComplete(function () {
      jQuery('.neo-btn.is-loading, .submit_button.is-loading').each(function () {
        var btn = jQuery(this);
        var original = btn.data('neo-original-html');
        if (original) {
          btn.html(original);
        }
        btn.removeClass('is-loading').prop('disabled', false);
      });
    });
  }

  function initToastrDefaults() {
    if (typeof toastr === 'undefined') {
      return;
    }

    toastr.options = Object.assign({}, toastr.options || {}, {
      closeButton: true,
      progressBar: true,
      newestOnTop: true,
      positionClass: document.documentElement.getAttribute('dir') === 'rtl'
        ? 'toast-top-left'
        : 'toast-top-right',
      preventDuplicates: true,
      timeOut: 4000,
      extendedTimeOut: 1000,
      showDuration: 300,
      hideDuration: 200,
      showMethod: 'fadeIn',
      hideMethod: 'fadeOut',
      rtl: document.documentElement.getAttribute('dir') === 'rtl' ||
        document.documentElement.getAttribute('data-textdirection') === 'rtl',
    });
  }

  function initSweetAlertDefaults() {
    if (typeof Swal === 'undefined') {
      return;
    }

    try {
      window.Swal = Swal.mixin({
        customClass: {
          popup: 'neo-swal',
          confirmButton: 'btn btn-primary',
          cancelButton: 'btn btn-outline-secondary ml-1',
        },
        buttonsStyling: false,
      });
    } catch (e) {
      /* SweetAlert not available on this page */
    }
  }

  window.neoSetButtonLoading = function (btn, loading) {
    if (!btn) {
      return;
    }
    var el = btn.jquery ? btn : jQuery(btn);
    if (loading) {
      if (!el.data('neo-original-html')) {
        el.data('neo-original-html', el.html());
      }
      el.addClass('is-loading').prop('disabled', true);
      el.html('<i class="fas fa-spinner fa-spin"></i>');
    } else {
      var original = el.data('neo-original-html');
      if (original) {
        el.html(original);
      }
      el.removeClass('is-loading').prop('disabled', false);
    }
  };
})();

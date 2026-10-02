document.addEventListener('DOMContentLoaded', function () {
    var body = document.body;

    /* ---------- Pop up Masuk / Daftar ---------- */
    var modal = document.getElementById('authModal');
    var lastFocus = null;

    function firstInput() {
        return modal.querySelector('.auth-panel:not([hidden]) input');
    }

    function showPanel(name) {
        modal.querySelectorAll('.auth-panel').forEach(function (panel) {
            panel.hidden = panel.getAttribute('data-panel') !== name;
        });
    }

    function isModalOpen() {
        return modal && modal.classList.contains('is-open');
    }

    function openModal(name) {
        if (!modal) return;
        var alreadyOpen = isModalOpen();
        if (!alreadyOpen) lastFocus = document.activeElement;

        if (window.NutriMate) window.NutriMate.closeSidebar();
        showPanel(name);
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        body.classList.add('modal-open');

        setTimeout(function () {
            var input = firstInput();
            if (input) input.focus();
        }, 60);
    }

    function closeModal() {
        if (!isModalOpen()) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        body.classList.remove('modal-open');
        if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    if (modal) {
        // Semua tombol/link yang membuka pop up (navbar, sidebar, CTA, pindah panel)
        document.querySelectorAll('[data-open-modal]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                openModal(el.getAttribute('data-open-modal'));
            });
        });

        // Tombol silang (X)
        modal.querySelectorAll('[data-close-modal]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        // Klik area blur di luar kartu -> tutup
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        // Tombol mata: tampil / sembunyikan password
        modal.querySelectorAll('.pw-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = btn.parentElement.querySelector('input');
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('on', show);
                btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });

        // Kalau pop up terbuka dari server (mis. ada error form), fokuskan input
        if (isModalOpen()) {
            var input = firstInput();
            if (input) input.focus();
        }
    }

    /* ---------- Keyboard: Esc & Tab ---------- */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            // Sidebar ditutup oleh ../assets/js/shared.js
            closeModal();
        }

        // Jaga fokus tetap di dalam pop up saat menekan Tab
        if (e.key === 'Tab' && isModalOpen()) {
            var focusable = modal.querySelectorAll(
                '.modal-close, .auth-panel:not([hidden]) a[href], .auth-panel:not([hidden]) input, .auth-panel:not([hidden]) button'
            );
            if (!focusable.length) return;
            var first = focusable[0];
            var last = focusable[focusable.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    /* ---------- Animasi muncul untuk kartu fitur & CTA ---------- */
    var items = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        items.forEach(function (el, i) {
            el.style.transitionDelay = (i % 4) * 80 + 'ms';
            observer.observe(el);
        });
    } else {
        items.forEach(function (el) { el.classList.add('visible'); });
    }
});
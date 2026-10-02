/* ============================================================
   NUTRIMATE — JS Global (reusable)
   ------------------------------------------------------------
   Berisi perilaku chrome yang sama di SEMUA fitur:
   - Buka/tutup sidebar mobile (tombol hamburger, overlay,
     klik link, Esc, dan resize ke desktop)
   - Bayangan navbar saat halaman di-scroll

   Dipakai SEMUA fitur. Letakkan SEBELUM script halaman:

       <script src="../assets/js/shared.js"></script>
       <script src="script.js"></script>

   API untuk script fitur:
       window.NutriMate.closeSidebar()   tutup sidebar (aman dipanggil kapan pun)
       window.NutriMate.isSidebarOpen()  cek status sidebar
   ============================================================ */
(function () {
    'use strict';

    /* ---------- Sidebar (mobile & tablet) ---------- */
    function setSidebar(open) {
        var menuBtn = document.getElementById('menuBtn');
        var sidebar = document.getElementById('sidebar');
        if (!menuBtn || !sidebar) return; // halaman tanpa sidebar → abaikan

        document.body.classList.toggle('sidebar-open', open);
        menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        sidebar.setAttribute('aria-hidden', open ? 'false' : 'true');
    }

    // API untuk script fitur — harus sudah tersedia sebelum DOM ready,
    // mis. dipakai saat fitur membuka pop up dari dalam sidebar.
    window.NutriMate = window.NutriMate || {};
    window.NutriMate.closeSidebar = function () { setSidebar(false); };
    window.NutriMate.isSidebarOpen = function () {
        return document.body.classList.contains('sidebar-open');
    };

    document.addEventListener('DOMContentLoaded', function () {
        var navbar = document.getElementById('navbar');
        var menuBtn = document.getElementById('menuBtn');
        var overlay = document.getElementById('overlay');
        var sidebar = document.getElementById('sidebar');

        if (menuBtn && overlay && sidebar) {
            menuBtn.addEventListener('click', function () {
                setSidebar(!window.NutriMate.isSidebarOpen());
            });

            overlay.addEventListener('click', function () {
                setSidebar(false);
            });

            // Pindah halaman lewat menu → sidebar ikut tertutup
            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    setSidebar(false);
                });
            });

            // Lewati breakpoint desktop → sidebar disembunyikan CSS, state ikut disinkronkan
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) setSidebar(false);
            });

            // Esc menutup sidebar. Pop up punya handler sendiri di script fitur.
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') setSidebar(false);
            });
        }

        /* ---------- Bayangan navbar saat di-scroll ---------- */
        if (navbar) {
            var onScroll = function () {
                navbar.classList.toggle('scrolled', window.scrollY > 10);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }
    });
})();

<?php
/* ============================================================
   NUTRIMATE — Komponen Navbar (global / reusable)
   ------------------------------------------------------------
   Dipakai SEMUA fitur. Cara pakai dari index.php suatu fitur:

       <?php include '../partials/navbar.php'; ?>

   Catatan path:
   - URL gambar dihitung relatif terhadap DOKUMEN (index.php fitur),
     bukan file ini. Jadi semua fitur harus setingkat di root
     (sejajar landingPage/ & foodDatabase/) agar ../assets/... valid.

   Variabel opsional yang boleh di-set SEBELUM include:
     $nav        array  [label, href, active] menu navigasi
     $isLoggedIn bool   status login
     $homeUrl    string url beranda (juga dipakai utk link logo)
     $urlMasuk   string url tombol Masuk
     $urlDaftar  string url tombol Daftar
   ============================================================ */
$nav        = $nav        ?? [];
$isLoggedIn = $isLoggedIn ?? false;
$homeUrl    = $homeUrl    ?? 'index.php';
$urlMasuk   = $urlMasuk   ?? 'index.php?modal=login';
$urlDaftar  = $urlDaftar  ?? 'index.php?modal=register';
$logoutUrl  = $homeUrl . '?status=logout';
?>
<header class="navbar" id="navbar">
    <div class="container nav-inner">
        <a href="<?= $homeUrl ?>" class="brand">
            <img src="../assets/img/logo.svg" alt="" class="brand-logo">
            <span class="brand-name">Nutri<b>Mate</b></span>
        </a>

        <!-- Menu desktop (tampil mulai layar >= 1024px) -->
        <nav class="nav-pill" aria-label="Menu utama">
            <?php foreach ($nav as [$label, $href, $active]): ?>
                <a href="<?= $href ?>" class="<?= $active ? 'active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </nav>

        <!-- Tombol akun desktop -->
        <div class="auth">
            <?php if ($isLoggedIn): ?>
                <a href="<?= $logoutUrl ?>" class="btn-sm btn-outline btn-logout">Keluar</a>
            <?php else: ?>
                <a href="<?= $urlMasuk ?>" class="btn-sm btn-outline" data-open-modal="login">Masuk</a>
                <a href="<?= $urlDaftar ?>" class="btn-sm btn-fill" data-open-modal="register">Daftar</a>
            <?php endif; ?>
        </div>

        <!-- Tombol menu mobile & tablet (sembunyi di desktop) -->
        <button class="menu-btn" id="menuBtn" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    </div>
</header>

<?php
/* ============================================================
   NUTRIMATE — Komponen Sidebar (global / reusable)
   ------------------------------------------------------------
   Dipakai SEMUA fitur. Cara pakai dari index.php suatu fitur:

       <?php include '../partials/sidebar.php'; ?>

   Varian mobil & tablet (sembunyi otomatis di layar >= 1024px).
   CSS-nya ada di ../assets/css/shared.css.

   Variabel opsional dari halaman pemanggil:
     $nav        array  [label, href, active] menu navigasi
     $isLoggedIn bool   status login
     $urlMasuk   string url tombol Masuk
     $urlDaftar  string url tombol Daftar
   ============================================================ */
$nav        = $nav        ?? [];
$isLoggedIn = $isLoggedIn ?? false;
$urlMasuk   = $urlMasuk   ?? 'index.php?modal=login';
$urlDaftar  = $urlDaftar  ?? 'index.php?modal=register';
$chevron = '<svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>';
?>
<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar" aria-hidden="true">
    <div class="side-brand">
        <img src="../assets/img/logo.svg" alt="" class="brand-logo">
        <div>
            <div class="brand-name">Nutri<b>Mate</b></div>
            <div class="tagline">Gizi terjaga, Hemat Terasa</div>
        </div>
    </div>

    <div class="side-label">General</div>
    <nav class="side-nav">
        <?php foreach ($nav as [$label, $href, $active]): ?>
            <a href="<?= $href ?>" class="side-item <?= $active ? 'active' : '' ?>">
                <span><?= $label ?></span><?= $chevron ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="side-label">Akun</div>
    <nav class="side-nav">
        <?php if ($isLoggedIn): ?>
            <a href="index.php?status=logout" class="side-item logout">
                <span>Keluar</span>
                <svg class="out" viewBox="0 0 24 24"><path d="M9 4H5v16h4M16 8l4 4-4 4M20 12H9"/></svg>
            </a>
        <?php else: ?>
            <a href="<?= $urlDaftar ?>" class="side-item active" data-open-modal="register">
                <span>Daftar</span><?= $chevron ?>
            </a>
            <a href="<?= $urlMasuk ?>" class="side-item" data-open-modal="login">
                <span>Masuk</span><?= $chevron ?>
            </a>
        <?php endif; ?>
    </nav>
</aside>
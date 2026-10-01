<?php
// Butuh variabel dari index.php: $isLoggedIn, $nav, $urlMasuk, $urlDaftar
$chevron = '<svg class="chev" viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg>';
?>
<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar" aria-hidden="true">
    <div class="side-brand">
        <img src="assets/img/logo.svg" alt="" class="brand-logo">
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
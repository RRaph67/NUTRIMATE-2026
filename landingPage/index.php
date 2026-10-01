<?php
session_start();

// Demo status login: buka index.php?status=login atau ?status=logout
if (isset($_GET['status'])) {
    $_SESSION['logged_in'] = ($_GET['status'] === 'login');
}

/* ---------- Proses form Masuk / Daftar (DEMO) ----------
   Belum ada database. Bagian ini hanya memvalidasi isian lalu menandai
   pengunjung sebagai "login". Ganti dengan pengecekan database nanti
   (password_hash / password_verify). */
$authError = '';
$openModal = '';
$name  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form  = $_POST['form'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($form === 'login') {
        $openModal = 'login';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $pass === '') {
            $authError = 'Email atau password belum valid.';
        } else {
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit;
        }
    } elseif ($form === 'register') {
        $openModal = 'register';
        $name    = trim($_POST['name'] ?? '');
        $confirm = $_POST['password_confirm'] ?? '';
        if ($name === '') {
            $authError = 'Nama lengkap wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $authError = 'Format email belum benar.';
        } elseif (strlen($pass) < 8) {
            $authError = 'Password minimal 8 karakter.';
        } elseif ($pass !== $confirm) {
            $authError = 'Konfirmasi password tidak sama.';
        } else {
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            header('Location: index.php');
            exit;
        }
    }
}

// Buka pop up lewat link (cadangan kalau JavaScript mati): index.php?modal=login
if ($openModal === '' && isset($_GET['modal']) && in_array($_GET['modal'], ['login', 'register'], true)) {
    $openModal = $_GET['modal'];
}

$isLoggedIn = !empty($_SESSION['logged_in']);
if ($isLoggedIn) {
    $openModal = '';
}

// Tujuan tombol Masuk & Daftar (membuka pop up)
$urlMasuk  = 'index.php?modal=login';
$urlDaftar = 'index.php?modal=register';

// Menu navigasi (dipakai navbar desktop & sidebar mobile/tablet)
$nav = [
    ['Home', 'index.php', true],
    ['Food Database', '#', false],
    ['Recommendation', '#', false],
    ['Calculate Nutritions', '#', false],
];
if ($isLoggedIn) {
    $nav[] = ['Profile', '#', false];
}

$features = [
    ['icon' => 'food',   'color' => 'yellow', 'title' => 'Food Database',
     'desc' => 'Katalog 1.500+ menu makanan lokal & warteg lengkap dengan estimasi harga riil per porsi.'],
    ['icon' => 'budget', 'color' => 'green',  'title' => 'Budget Recommendation',
     'desc' => 'Rekomendasi paket makan sehat 3x sehari otomatis disesuaikan dengan sisa uang saku.'],
    ['icon' => 'plan',   'color' => 'orange', 'title' => 'Meal Planner',
     'desc' => 'Atur jadwal makan mingguan secara rapi dan sinkronkan dengan sisa saldo bulanan kost.'],
    ['icon' => 'calc',   'color' => 'red',    'title' => 'Nutrition Calculator',
     'desc' => 'Hitung kebutuhan kalori harian dan pantau makronutrisi agar badan tetap bugar sepanjang kuliah.'],
];

$icons = [
    'food'   => '<path d="M4 10h16l-1.5 9h-13L4 10zM3 7l2-3h14l2 3v3H3V7z"/>',
    'budget' => '<path d="M19 12c0-3-3-5-7-5S5 9 5 12c0 2 1 3.5 2.5 4.3V19h3v-1.5h3V19h3v-2.7c1.5-.8 2.5-2.3 2.5-4.3z"/><path d="M19 11h2v3h-2M9 11h.01"/>',
    'plan'   => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>',
    'calc'   => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h3M9.5 5.5v3M14 7h2M8 13h3M14 13h2M14 17h2M8.5 15v4"/>',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NutriMate</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="<?= $openModal ? 'modal-open' : '' ?>">
    <header class="navbar" id="navbar">
        <div class="container nav-inner">
            <a href="index.php" class="brand">
                <img src="assets/img/logo.svg" alt="" class="brand-logo">
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
                    <a href="index.php?status=logout" class="btn-sm btn-outline btn-logout">Keluar</a>
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

    <?php include 'includes/sidebar.php'; ?>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-text">
                    <span class="badge">
                        <svg viewBox="0 0 24 24"><path d="M12 21v-9M12 12c0-4 3-6 7-6 0 4-3 6-7 6zM12 14c0-3-2-5-6-5 0 3 2 5 6 5z"/></svg>
                        Solusi Cerdas Mahasiswa
                    </span>
                    <h1>Makan Sehat,<br><span>Kantong Aman.</span></h1>
                    <p>Aplikasi pengatur pola makan harian mahasiswa kost untuk capai target nutrisi harian optimal tanpa bikin dompet jebol di akhir bulan.</p>
                    <a href="#fitur" class="btn-main">Mulai Sekarang
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <div class="hero-visual">
                    <div class="photo-card">
                        <img src="assets/img/hero.svg" alt="Meal prep makanan sehat" onerror="this.remove()">
                    </div>
                    <div class="float-info">
                        <span class="float-icon">
                            <svg viewBox="0 0 24 24"><path d="M6 3l12 12M18 3L6 15M5 20l4-4M15 21c3-1 5-3 4-6"/></svg>
                        </span>
                        <div class="float-text">
                            <strong><i class="dot"></i>Target Gizi Harian: 2.100 kkal</strong>
                            <span>Estimasi Pengeluaran: <b>Rp 22.500/hari</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features" id="fitur">
            <div class="container">
                <div class="section-head">
                    <span class="badge badge-sm">Fitur Pilihan</span>
                    <h2>Fitur Utama NutriMate</h2>
                    <p>Solusi terintegrasi yang memudahkan anak kost makan bergizi hemat setiap hari.</p>
                </div>

                <div class="feature-grid">
                    <?php foreach ($features as $f): ?>
                        <article class="card reveal">
                            <div class="icon-box <?= $f['color'] ?>">
                                <svg viewBox="0 0 24 24"><?= $icons[$f['icon']] ?></svg>
                            </div>
                            <h3><?= htmlspecialchars($f['title']) ?></h3>
                            <p><?= htmlspecialchars($f['desc']) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="cta-wrap">
            <div class="cta reveal">
                <span class="badge-light">Mulai Hemat &amp; Sehat</span>
                <h2>Siap Atur Budget Makanmu?</h2>
                <p>Gabung bersama ribuan mahasiswa lainnya dan mulai merencanakan nutrisi dengan dompet harianmu.</p>
                <?php if ($isLoggedIn): ?>
                    <a href="#fitur" class="btn-yellow">Lihat Fitur</a>
                <?php else: ?>
                    <a href="<?= $urlDaftar ?>" class="btn-yellow" data-open-modal="register">Daftar Sekarang</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <div class="foot-left">
                <img src="assets/img/logo.svg" alt="" class="foot-logo">
                <strong>NutriMate</strong>
                <span>© 2026 NutriMate. Hak Cipta Dilindungi.</span>
            </div>
            <nav class="foot-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Bantuan &amp; FAQ</a>
            </nav>
        </div>
    </footer>

    <?php if (!$isLoggedIn) { include 'includes/auth-modal.php'; } ?>

    <script src="assets/js/script.js"></script>
</body>
</html>
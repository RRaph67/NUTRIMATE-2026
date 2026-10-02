<?php
session_start();

/* Status login — dipakai navbar & sidebar yang sama di semua fitur */
$isLoggedIn = !empty($_SESSION['logged_in']);

/* Menu navigasi. Hanya Food Database yang aktif di halaman ini. */
$nav = [
    ['Home', '../landingPage/index.php', false],
    ['Food Database', 'index.php', true],
    ['Recommendation', '#', false],
    ['Calculate Nutritions', '#', false],
];
if ($isLoggedIn) {
    $nav[] = ['Profile', '#', false];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NutriMate - Database Makanan &amp; Nutrisi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shared.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php include '../partials/navbar.php'; ?>
    <?php include '../partials/sidebar.php'; ?>

    <main class="page">
        <div class="container">

            <!-- Kepala halaman -->
            <div class="page-head">
                <span class="page-badge">Katalog Nutrisi</span>
                <h1 class="page-title">Database Makanan &amp; Nutrisi</h1>
                <p class="page-subtitle">Katalog makanan bernutrisi ramah kantong mahasiswa dan anak kost.</p>
            </div>

            <!-- Search & Category Filter -->
            <div class="search-filter-box">
                <div class="search-row">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari makanan..." aria-label="Cari makanan" autocomplete="off">
                    </div>
                    <select class="sort-select" id="sortSelect" aria-label="Urutkan makanan">
                        <option value="cheap">Termurah</option>
                        <option value="cal">Kalori Rendah</option>
                    </select>
                </div>
                <div class="category-pills" id="categoryPills">
                    <button type="button" class="pill active" data-category="Semua">Semua</button>
                    <button type="button" class="pill" data-category="Karbohidrat">Karbohidrat</button>
                    <button type="button" class="pill" data-category="Protein">Protein</button>
                    <button type="button" class="pill" data-category="Sayuran">Sayuran</button>
                    <button type="button" class="pill" data-category="Buah">Buah</button>
                    <button type="button" class="pill" data-category="Minuman">Minuman</button>
                </div>
            </div>

            <!-- Food Grid (diisi oleh script.js) -->
            <div class="food-grid" id="foodGrid" aria-live="polite"></div>

        </div>
    </main>

    <!-- Modal Popup Detail Makanan -->
    <div class="modal-overlay" id="modalOverlay" aria-hidden="true">
        <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
            <div class="modal-img-wrapper">
                <span class="category-badge" id="modalBadge"></span>
                <button type="button" class="btn-close" id="btnCloseModal" aria-label="Tutup">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
                <img id="modalImg" src="" alt="">
            </div>
            <div class="modal-content">
                <h2 class="modal-title" id="modalTitle"></h2>
                <div class="modal-price"><span id="modalPrice"></span> <span id="modalUnit"></span></div>

                <div class="modal-desc-box" id="modalDesc"></div>

                <div class="macro-title">Kandungan Makronutrisi:</div>
                <div class="macro-grid">
                    <div class="macro-box">
                        <div class="macro-label">Kalori</div>
                        <div class="macro-val" id="modalCal"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Protein</div>
                        <div class="macro-val highlight" id="modalProtein"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Karbo</div>
                        <div class="macro-val" id="modalCarbs"></div>
                    </div>
                    <div class="macro-box">
                        <div class="macro-label">Lemak</div>
                        <div class="macro-val" id="modalFat"></div>
                    </div>
                </div>

                <button type="button" class="btn-add-planner">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/></svg>
                    Tambah ke Meal Planner
                </button>
            </div>
        </div>
    </div>

    <?php include '../partials/footer.php'; ?>

    <script src="../assets/js/shared.js"></script>
    <script src="script.js"></script>
</body>
</html>

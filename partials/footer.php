<?php
/* ============================================================
   NUTRIMATE — Komponen Footer (global / reusable)
   ------------------------------------------------------------
   Dipakai SEMUA fitur. Cara pakai dari index.php suatu fitur:

       <?php include '../partials/footer.php'; ?>

   Kontennya statis (brand global), jadi tidak butuh variabel.
   URL gambar relatif terhadap DOKUMEN (index.php fitur), jadi
   semua fitur harus setingkat di root agar ../assets/... valid.
   ============================================================ */
?>
<footer class="footer">
    <div class="container footer-inner">
        <div class="foot-left">
            <img src="../assets/img/logo.svg" alt="" class="foot-logo">
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

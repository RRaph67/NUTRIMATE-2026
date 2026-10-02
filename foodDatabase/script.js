document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    /* ===================== State ===================== */
    let allFoods = [];
    let currentCategory = "Semua";
    let currentSort = "cheap"; // sesuai option pertama di #sortSelect
    let state = "loading";     // loading | ready | error
    let lastFocused = null;

    const foodGrid = document.getElementById("foodGrid");
    const searchInput = document.getElementById("searchInput");
    const sortSelect = document.getElementById("sortSelect");
    const pills = Array.from(document.querySelectorAll(".pill"));
    const modalOverlay = document.getElementById("modalOverlay");
    const btnCloseModal = document.getElementById("btnCloseModal");
    const modalCard = modalOverlay.querySelector(".modal-card");
    const modalImg = document.getElementById("modalImg");

    /* ===================== Helpers ===================== */

    /** Semua data dari API diperlakukan tidak tepercaya → escape dulu. */
    function escapeHtml(value) {
        return String(value === null || value === undefined ? "" : value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    function toInt(value) {
        const n = Number(value);
        return Number.isFinite(n) ? Math.trunc(n) : 0;
    }

    function rupiah(value) {
        return "Rp " + toInt(value).toLocaleString("id-ID");
    }

    /** Hanya izinkan http/https — tolak javascript:, data:, dan URL rusak. */
    function safeImage(url) {
        if (!url) return "";
        try {
            const parsed = new URL(String(url), window.location.href);
            return parsed.protocol === "http:" || parsed.protocol === "https:" ? parsed.href : "";
        } catch (err) {
            return "";
        }
    }

    /* ===================== Data ===================== */

    async function fetchFoods() {
        state = "loading";
        renderFoods();

        try {
            const res = await fetch("api.php", {
                headers: { Accept: "application/json" },
                cache: "no-store"
            });
            if (!res.ok) throw new Error("HTTP " + res.status);

            const result = await res.json();
            if (!result || result.status !== "success" || !Array.isArray(result.data)) {
                throw new Error((result && result.message) || "Format data tidak valid");
            }

            allFoods = result.data;
            state = "ready";
        } catch (err) {
            console.error("Gagal mengambil data makanan:", err);
            allFoods = [];
            state = "error";
        }

        renderFoods();
    }

    /* ===================== Filter, cari & urutkan ===================== */

    function getVisibleFoods() {
        const query = searchInput.value.trim().toLowerCase();

        const list = allFoods.filter((food) => {
            const matchCategory =
                currentCategory === "Semua" ||
                String(food.category || "").toLowerCase() === currentCategory.toLowerCase();
            const matchSearch =
                String(food.name || "").toLowerCase().indexOf(query) !== -1;
            return matchCategory && matchSearch;
        });

        // Select urutkan selalu berlaku, jadi labelnya tidak menyesatkan.
        list.sort((a, b) =>
            currentSort === "cal"
                ? toInt(a.calories) - toInt(b.calories)
                : toInt(a.price) - toInt(b.price)
        );

        return list;
    }

    /* ===================== Render ===================== */

    function statusBlock(title, message, showRetry) {
        return (
            '<div class="grid-status">' +
                '<span class="status-title">' + escapeHtml(title) + "</span>" +
                "<span>" + escapeHtml(message) + "</span>" +
                (showRetry ? '<br><button type="button" class="btn-retry">Coba Lagi</button>' : "") +
            "</div>"
        );
    }

    function cardTemplate(food) {
        const image = safeImage(food.image);
        const name = escapeHtml(food.name);
        const category = escapeHtml(food.category || "Lainnya");

        return (
            '<article class="food-card">' +
                '<div class="card-img-wrapper">' +
                    '<span class="category-badge">' + category + "</span>" +
                    (image
                        ? '<img src="' + escapeHtml(image) + '" alt="' + name + '" loading="lazy">'
                        : "") +
                "</div>" +
                '<div class="card-body">' +
                    '<h3 class="card-title">' + name + "</h3>" +
                    '<div class="card-meta">' +
                        '<div class="card-price">' +
                            rupiah(food.price) +
                            " <span>/ " + escapeHtml(food.portion_unit || "porsi") + "</span>" +
                        "</div>" +
                        '<div class="card-calories">' + toInt(food.calories) + " kcal</div>" +
                    "</div>" +
                    '<button type="button" class="btn-detail" data-food-id="' + toInt(food.id) + '">' +
                        '<svg viewBox="0 0 24 24" aria-hidden="true">' +
                            '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>' +
                        "</svg>" +
                        "Detail &amp; Tambah" +
                    "</button>" +
                "</div>" +
            "</article>"
        );
    }

    function renderFoods() {
        if (state === "loading") {
            foodGrid.innerHTML = statusBlock("Memuat data…", "Mohon tunggu sebentar.", false);
            return;
        }
        if (state === "error") {
            foodGrid.innerHTML = statusBlock("Gagal memuat data", "Periksa koneksi lalu coba lagi.", true);
            return;
        }

        const visible = getVisibleFoods();
        foodGrid.innerHTML = visible.length
            ? visible.map(cardTemplate).join("")
            : statusBlock("Makanan tidak ditemukan", "Coba kata kunci atau kategori lain.", false);
    }

    /* ===================== Pop up detail ===================== */

    function isModalOpen() {
        return modalOverlay.classList.contains("is-open");
    }

    function fillModal(food) {
        document.getElementById("modalBadge").textContent = food.category || "Lainnya";
        document.getElementById("modalTitle").textContent = food.name || "-";
        document.getElementById("modalPrice").textContent = rupiah(food.price);
        document.getElementById("modalUnit").textContent = "/ " + (food.portion_unit || "porsi");
        document.getElementById("modalDesc").textContent =
            food.description || "Tidak ada deskripsi rinci.";
        document.getElementById("modalCal").textContent = toInt(food.calories) + " kcal";
        document.getElementById("modalProtein").textContent = toInt(food.protein) + "g";
        document.getElementById("modalCarbs").textContent = toInt(food.carbs) + "g";
        document.getElementById("modalFat").textContent = toInt(food.fat) + "g";

        const src = safeImage(food.image);
        if (src) {
            modalImg.src = src;
            modalImg.alt = food.name || "";
            modalImg.hidden = false;
        } else {
            modalImg.removeAttribute("src");
            modalImg.alt = "";
            modalImg.hidden = true;
        }
    }

    function openModal(id) {
        const food = allFoods.find((item) => toInt(item.id) === id);
        if (!food) return;

        lastFocused = document.activeElement;
        fillModal(food);

        modalOverlay.classList.add("is-open");
        modalOverlay.setAttribute("aria-hidden", "false");
        document.body.classList.add("modal-open");
        btnCloseModal.focus();
    }

    function closeModal() {
        if (!isModalOpen()) return;

        modalOverlay.classList.remove("is-open");
        modalOverlay.setAttribute("aria-hidden", "true");
        document.body.classList.remove("modal-open");

        if (lastFocused && typeof lastFocused.focus === "function") lastFocused.focus();
        lastFocused = null;
    }

    /** Fokus tidak boleh keluar dari pop up selama terbuka. */
    function trapFocus(event) {
        const focusable = modalCard.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }

    /* ===================== Event ===================== */

    // Satu listener delegasi untuk semua kartu + tombol "Coba Lagi"
    // (menggantikan onclick inline & window.openModal).
    foodGrid.addEventListener("click", (event) => {
        if (event.target.closest(".btn-retry")) {
            fetchFoods();
            return;
        }
        const detail = event.target.closest("[data-food-id]");
        if (detail) openModal(toInt(detail.getAttribute("data-food-id")));
    });

    // Gambar gagal dimuat → lepas, kartu tetap rapi.
    // (error tidak menggelembung, jadi pakai fase capture)
    foodGrid.addEventListener(
        "error",
        (event) => {
            if (event.target && event.target.tagName === "IMG") event.target.remove();
        },
        true
    );

    modalImg.addEventListener("error", () => {
        modalImg.hidden = true;
    });

    searchInput.addEventListener("input", renderFoods);

    sortSelect.addEventListener("change", () => {
        currentSort = sortSelect.value;
        renderFoods();
    });

    pills.forEach((pill) => {
        pill.addEventListener("click", () => {
            pills.forEach((item) => item.classList.remove("active"));
            pill.classList.add("active");
            currentCategory =
                pill.getAttribute("data-category") || pill.textContent.trim();
            renderFoods();
        });
    });

    btnCloseModal.addEventListener("click", closeModal);

    modalOverlay.addEventListener("click", (event) => {
        if (event.target === modalOverlay) closeModal();
    });

    document.addEventListener("keydown", (event) => {
        if (!isModalOpen()) return;
        if (event.key === "Escape") {
            closeModal();
            return;
        }
        if (event.key === "Tab") trapFocus(event);
    });

    fetchFoods();
});

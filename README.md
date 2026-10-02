<div align="center">
  <img src="assets/img/logo.svg" alt="NutriMate logo" width="180" />

  # NutriMate

  **Aplikasi web pengelolaan nutrisi dan rencana makan untuk mahasiswa — kelola database makanan, hitung kebutuhan gizi, susun meal planner, dan cari rekomendasi makanan sesuai budget.**

  <br />

  ![Platform](https://img.shields.io/badge/Platform-Web-4A90E2?style=for-the-badge)
  ![Language](https://img.shields.io/badge/Language-PHP-777BB4?style=for-the-badge)
  ![Server](https://img.shields.io/badge/Server-XAMPP-FB7A24?style=for-the-badge)
  ![Storage](https://img.shields.io/badge/Storage-JSON%20File-718A14?style=for-the-badge)

</div>

---

## Table of contents

- [Project overview](#project-overview)
- [Key features](#key-features)
- [Technology stack](#technology-stack)
- [Project structure](#project-structure)
- [Getting Started](#getting-started)
- [Team](#team)

## Project overview

| Item | Details |
| --- | --- |
| Application Type | Web (Server-side rendered) |
| Primary Platform | Desktop & Mobile Browser |
| <!-- PLACEHOLDER --> | <!-- isi tambahan, mis. Kelas / Mata Kuliah --> |

<!-- PLACEHOLDER: tulis deskripsi proyekmu sendiri -->

NutriMate 2026 adalah aplikasi web pengelolaan nutrisi yang membantu pengguna memahami kebutuhan gizi harian, mencatat makanan, dan menyusun rencana makan yang sesuai dengan budget. Data makanan dikelola lewat REST API sederhana berbasis file JSON, sehingga mudah dijalankan lokal tanpa database tambahan.

## Key features

| Feature | What the user can do |
| --- | --- |
| **Food Database** | Melihat, mencari, menambah, mengubah, dan menghapus data makanan beserta nilai gizinya melalui antarmuka kartu yang responsif. |
| **Budget Recommendation** | Mendapatkan rekomendasi makanan sesuai budget yang dimiliki. <!-- PLACEHOLDER: sesuaikan deskripsi --> |
| **Meal Planner** | Menyusun rencana makan harian/mingguan. <!-- PLACEHOLDER: sesuaikan deskripsi --> |
| **Nutrition Calculator** | Menghitung kebutuhan dan asupan nutrisi harian. <!-- PLACEHOLDER: sesuaikan deskripsi --> |
| **Autentikasi** | Mendaftar dan masuk ke akun melalui modal login yang dapat dibuka dari navbar maupun sidebar. <!-- PLACEHOLDER: sesuaikan --> |
| **Responsive UI** | Mengakses seluruh halaman di berbagai ukuran layar dengan sidebar hamburger dan navigasi yang konsisten. |

## Technology stack

| Category | Technology | Purpose |
| --- | --- | --- |
| Frontend | HTML + CSS + Vanilla JavaScript | UI tanpa framework, ringan dan mudah dipelajari |
| Backend | PHP | Penyajian halaman dan REST API (`api.php`) |
| Architecture | Partial-based (reusable components) | Navbar, sidebar, dan footer dipakai ulang lewat `partials/` |
| Design System | Custom CSS tokens (`rules.md`) | Variable `:root`, skala tipografi, spacing, radius, shadow, dan breakpoint yang seragam |
| Shared Behavior | `assets/js/shared.js` | Toggle sidebar, bayangan navbar saat scroll, API `window.NutriMate` |
| Storage | JSON file (`foods.json`) | Penyimpanan data makanan tanpa database eksternal |
| Server | Apache (XAMPP) | Menjalankan aplikasi secara lokal |
| Typography | Poppins | Font utama (bobot 400/500/600/700) |

## Project structure

```text
nutrimate2026/
├── assets/
│   ├── css/
│   │   └── shared.css        # Token design system + navbar/sidebar/footer
│   ├── js/
│   │   └── shared.js         # Perilaku global (sidebar, navbar scroll)
│   └── img/
│       ├── logo.svg          # Logo NutriMate
│       └── hero.svg          # Ilustrasi hero landing page
├── partials/
│   ├── navbar.php            # Komponen navigasi atas (reusable)
│   ├── sidebar.php           # Komponen sidebar mobile (reusable)
│   └── footer.php            # Komponen footer (reusable)
├── landingPage/
│   ├── index.php             # Halaman utama (hero, fitur, CTA)
│   ├── auth-modal.php        # Modal login & register
│   ├── style.css             # Konten khusus halaman landing
│   └── script.js             # Interaksi halaman landing
├── foodDatabase/
│   ├── index.php             # Halaman database makanan
│   ├── api.php               # REST API (GET/POST/PATCH/DELETE)
│   ├── foods.json            # Data makanan (file JSON)
│   ├── style.css             # Konten khusus halaman food database
│   └── script.js             # Render kartu, form, sorting, escaping
├── rules.md                  # Aturan design system & konvensi proyek
└── README.md
```

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

*   [XAMPP](https://www.apachefriends.org/) terpasang (Apache + PHP).
*   Editor seperti VS Code.
*   <!-- PLACEHOLDER: kebutuhan lain, mis. Browser Chrome terbaru -->

### Installation

1.  **Clone the repo**
    ```sh
    git clone <!-- PLACEHOLDER: ganti dengan URL repo kamu -->
    ```
2.  **Move the project into `htdocs`**
    ```sh
    mv nutrimate2026 /path/to/xampp/htdocs/
    ```
3.  **Start Apache**
    Buka XAMPP Control Panel, lalu klik **Start** pada module **Apache**.
4.  **Open the app**
    Buka browser dan akses:
    ```text
    http://localhost/nutrimate2026/landingPage/index.php
    ```
    <!-- PLACEHOLDER: sesuaikan path jika nama folder berbeda -->

---

## Team

| Name | Role | Responsibilities | Contact |
| --- | --- | --- | --- |
| <!-- PLACEHOLDER --> | | | |
| <!-- PLACEHOLDER --> | | | |
| <!-- PLACEHOLDER --> | | | |

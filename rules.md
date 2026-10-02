# NutriMate — Design System & Aturan Konsistensi

> Dokumen ini **mengikat**. Setiap fitur baru wajib mengacu ke sini.
> Sumber kebenaran (single source of truth):
>
> | Peran | File |
> |---|---|
> | Design token, layout dasar, Navbar, Sidebar, Footer | [`assets/css/shared.css`](assets/css/shared.css) |
> | Perilaku sidebar & bayangan navbar | [`assets/js/shared.js`](assets/js/shared.js) |
> | Referensi tampilan & komponen halaman | [`landingPage/style.css`](landingPage/style.css) |
> | Komponen HTML reusable | [`partials/`](partials/) |
>
> **Prinsip utama:** `landingPage` adalah acuan. Fitur lain **mengikuti**, bukan mengubah.

---

## 1. Cakupan

Berlaku untuk **semua** fitur (`landingPage/`, `foodDatabase/`, dan fitur berikutnya).

Kalau menemui nilai yang tidak ada di dokumen ini:
1. **Jangan membuat nilai baru** kalau bisa memakai yang sudah ada.
2. Kalau benar-benar perlu, tambahkan ke `rules.md` di pull request yang sama.

---

## 2. Struktur Folder & Path

```
nutrimate2026/
├── assets/                  ← ASET GLOBAL (jangan digandakan per fitur)
│   ├── css/shared.css       ← wajib di-link SEMUA fitur
│   ├── js/shared.js         ← wajib di-load SEMUA fitur
│   └── img/                 ← logo, hero, gambar bersama
├── partials/                ← KOMPONEN HTML reusable
│   ├── navbar.php
│   ├── sidebar.php
│   └── footer.php
├── rules.md
└── <nama-fitur>/            ← selalu SETINGKAT di root
    ├── index.php
    ├── style.css            ← CSS khusus halaman itu saja
    ├── script.js
    └── (partial lokal yang benar-benar spesifik fitur, mis. auth-modal.php)
```

**Aturan path (wajib):**

```html
<!-- urutan di <head> — shared.css DULU -->
<link rel="stylesheet" href="../assets/css/shared.css">
<link rel="stylesheet" href="style.css">
```

```php
<?php include '../partials/navbar.php'; ?>
<?php include '../partials/sidebar.php'; ?>
<?php include '../partials/footer.php'; ?>
```

```html
<!-- urutan di akhir <body> — shared.js DULU -->
<script src="../assets/js/shared.js"></script>
<script src="script.js"></script>
```

- Aset diakses dengan `../assets/...` karena semua fitur **setingkat di root**.
- URL gambar di browser dihitung relatif ke **dokumen** (`<fitur>/index.php`), bukan file partial — jadi `../assets/img/logo.svg` valid dari partial mana pun.
- **Dilarang** menyalin `shared.css`, `shared.js`, `navbar.php`, atau aset gambar ke dalam folder fitur.

---

## 3. Color Palette

### 3.1 Token inti — `:root` (wajib pakai variabel)

```css
:root {
    --green:      #718a14;   /* primer / aksi utama */
    --green-dark: #5f7710;   /* primer gelap / heading & link */
    --cream:      #fdf8f1;   /* background halaman */
    --section:    #f8f5ee;   /* background section alternatif */
    --yellow:     #f8d88c;   /* aksen highlight / CTA sekunder */
    --text:       #1c1c1a;   /* teks utama */
    --muted:      #55554f;   /* teks sekunder */
    --red:        #e8310c;   /* danger / logout */
}
```

| Token | Hex | Pakai untuk |
|---|---|---|
| `--green` | `#718a14` | Tombol primer, nav aktif, item sidebar aktif, CTA, judul brand |
| `--green-dark` | `#5f7710` | Brand name, link footer, label sidebar, ikon menu |
| `--cream` | `#fdf8f1` | `background` halaman, navbar |
| `--section` | `#f8f5ee` | Background section (mis. blok fitur) |
| `--yellow` | `#f8d88c` | Tombol CTA, ikon float |
| `--text` | `#1c1c1a` | Paragraf, heading |
| `--muted` | `#55554f` | Deskripsi, meta, footer |
| `--red` | `#e8310c` | Tombol Keluar, item logout |

> **Aturan:** 8 token di atas **tidak boleh diganti**. Jangan membuat `--primary-olive`,
> `--bg-cream`, atau nama varian lain.

### 3.2 Permukaan & garis (netral hangat)

| Warna | Pemakaian |
|---|---|
| `#fff` | Kartu, panel modal, input fokus, foto-card |
| `#f6f2ec` | Background footer |
| `#f6f6f6` | Background input |
| `#ece5d8` | Border bawah navbar |
| `#e8e1d4` | Border atas footer |
| `#e6e0d3` | Border tombol outline |
| `#ececec` | Border input, divider modal |
| `#f3f0e8` | Background nav-pill, hover tombol outline |
| `#e8e4d8` | Hover nav-pill |
| `#f0f4e4` | Hover item sidebar |
| `#ecefd9` | Background badge |
| `#a3a39d` | Placeholder input |
| `#6b6b64` | Ikon tombol lihat password |

### 3.3 Tint status (khusus kotak ikon & pesan)

| Keluarga | Background | Ikon/teks |
|---|---|---|
| Kuning | `#fdf0cc` | `#c88a0a` |
| Hijau | `#e4ecd0` | `var(--green-dark)` |
| Oranye | `#fde9d0` | `#d9731a` |
| Merah | `#fbdfdb` | `#d6381f` |
| Error form | `#fdeceb` | `#b3270a` (border bahaya: `#f3c9c0`) |

### 3.4 Aturan pemakaian warna

- ✅ **Wajib** `var(--green)` untuk aksi utama, bukan hex baru.
- ✅ Tint di §3.3 hanya untuk **kartu/kotak kecil**, bukan background section.
- ✅ Teks di atas `--green` selalu `#fff`.
- ❌ **Dilarang** memakai warna di luar daftar (terutama hijau/olive lain).
- ❌ **Dilarang** `background` gelap pekat — nuansa proyek ini terang & hangat (cream).
- ❌ **Dilarang** `!important` kecuali `[hidden]`.

---

## 4. Typography

### 4.1 Font

- **Family:** `Poppins` — satu-satunya font. Fallback: `sans-serif`.
- **Bobot yang dipakai saja:** `400` (default), `500`, `600`, `700`.
- **Cara muat:** `<link>` di `<head>` HTML, **bukan** `@import` di CSS.

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

- `body` sudah di-set `font-family: 'Poppins'` + `-webkit-font-smoothing: antialiased` di `shared.css` — jangan ulangi di tiap selector.

> ❌ **Dilarang** `font-weight: 800`/`900`, family lain, dan `@import` font di CSS.

### 4.2 Skala ukuran teks (px)

Dipakai **hanya** nilai berikut, per peran:

| Peran | Mobile | Tablet ≥768 | Desktop ≥1024 | Weight | Line-height |
|---|---|---|---|---|---|
| `h1` hero | 26 | 36 | 42 | 700 | 1.12–1.15 |
| `h2` section / CTA | 22 / 21 | 25 | 26 | 700 | 1.25 |
| `h3` judul kartu | 15 | – | 17 | 600 | 1.4 |
| Judul pop up | 28 | 31 | – | 700 | 1.1 |
| Brand name | 17 | – | – | 700 | 1.1 |
| Body / paragraf | 12–14 | 14 | 15 | 400 | 1.6 |
| Teks kartu / meta | 12–13 | 13 | 13–14 | 400 | 1.6 |
| Tombol | 13 | – | – | 600 | – |
| Label / badge | 10–11 | 11 | – | 500–600 | – |
| Footer | 11 | 13 | – | 600 | – |
| **Input** | **16** | 16 | 16 | 400 | – |

> ⚠️ **Input harus tetap `16px`.** Di iOS/Safari, input < 16px memicu auto-zoom saat
> diklik — itu bug UX. Padding-nya yang dikecilkan (`12px 16px`), bukan font-size-nya.

**Line-height** hanya memakai: `1.1`, `1.12`, `1.15` (heading besar) · `1.25` (h2) · `1.4` (h3) · `1.6` (paragraf).
**Letter-spacing** hanya untuk heading besar: `-.2px` (mobile) / `-.4px` (desktop).

---

## 5. Spacing & Layout

### 5.1 Container

```css
.container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 18px; }
/* ≥768px  → padding: 0 28px */
/* ≥1024px → padding: 0 36px */
```

`max-width: 1280px` **dipakai semua fitur**. Jangan menyetel lebar container sendiri.

### 5.2 Breakpoint (mobile-first, `min-width`)

| Nama | Nilai |
|---|---|
| Mobile | default (base) |
| Tablet | `@media (min-width: 768px)` |
| Desktop | `@media (min-width: 1024px)` |

Hanya **dua** breakpoint. Jangan menambah breakpoint di luar ini.

### 5.3 Skala jarak

Gunakan nilai yang sudah ada; urut dari kecil ke besar:

**Gap (jarak dalam flex/grid)** — hanya 11 nilai ini, tanpa kecuali:

`3 · 5 · 7 · 8 · 11 · 13 · 16 · 18 · 22 · 32 · 56`

| Kategori | Gap | Contoh pakai |
|---|---|---|
| Micro | 3, 5, 7, 8 | ikon ↔ teks, label ↔ isi |
| Element | 11, 13 | item sidebar, judul ↔ deskripsi |
| Component | 16, 18 | grid kartu, kolom grid |
| Block | 22, 32 | antar blok besar, grid hero tablet |
| Section | 56 | kolom hero desktop |

**Padding & margin** — selalu pilih dari skala yang sudah terpakai di kode:

`5 6 7 8 9 10 11 12 13 14 15 16 18 20 22 24 26 28 30 32 34 36 40 42 44 46 48 56 64`

> ❌ **Dilarang mengarang nilai baru.** Kalau nilai terdekat terasa meleset,
> pertimbangkan apakah komponennya memang perlu diubah — dan catat di `rules.md`.
> Konsistensi > presisi.

### 5.4 Grid fitur

```css
.feature-grid { display: grid; gap: 11px; }              /* mobile: 1 kolom */
/* ≥768  → repeat(2, 1fr); gap: 16px */
/* ≥1024 → repeat(4, 1fr); gap: 18px */
```

---

## 6. Border Radius

| Nilai | Dipakai untuk |
|---|---|
| `99px` | **Pill** — semua tombol, badge, input, nav-pill, item sidebar *(paling sering)* |
| `50%` | Lingkaran — avatar, ikon bulat, tombol close |
| `9px` | Tombol menu (hamburger) |
| `10px` | Kotak ikon fitur (mobile) |
| `12px` | Panel pesan error |
| `14px` | Kartu (mobile) |
| `15px` | Foto hero (isi dalam, tablet) |
| `16px` | Foto hero (mobile) |
| `17px` | Kartu (desktop) |
| `18px` | Panel CTA |
| `21px` | Bingkai foto hero (tablet) |
| `22px` | Panel CTA (tablet), modal, foto hero (desktop) |

> **Aturan:** elemen yang bisa diklik/berupa pill → `99px`. Elemen permukaan → antara `14px`–`22px`.

---

## 7. Shadow & Elevation

Tiga keluarga — **jangan dicampur**:

**a) Hijau (brand)** — untuk elemen aksi
```css
0 6px 14px rgba(113, 138, 20, .25)   /* tombol */
0 8px 20px rgba(113, 138, 20, .25)   /* tombol utama, CTA */
0 12px 26px rgba(113, 138, 20, .32)  /* hover tombol utama */
0 0 0 4px rgba(113, 138, 20, .15)    /* focus ring input */
```

**b) Cokelat hangat (netral proyek)** — untuk permukaan naik
```css
0 2px 10px  rgba(60, 50, 20, .06)    /* kartu */
0 4px 18px  rgba(60, 50, 20, .08)    /* navbar saat scroll */
0 14px 30px rgba(60, 50, 20, .1)     /* kartu hover */
0 18px 40px rgba(60, 50, 20, .12)    /* foto hero tablet */
0 12px 30px rgba(60, 50, 20, .15)    /* float info */
```

**c) Hitam netral** — hanya untuk overlay/mode modal
```css
0 8px 18px rgba(0, 0, 0, .15)        /* tombol CTA kuning */
0 30px 70px rgba(0, 0, 0, .25)       /* kartu modal */
-8px 0 30px rgba(0, 0, 0, .18)       /* sidebar */
```

> ❌ **Dilarang** `rgba(0,0,0, ...)` untuk kartu biasa — akan terasa "berat" dan
> tidak selaras dengan nuansa hangat proyek.

---

## 8. Z-Index

| Nilai | Elemen |
|---|---|
| `1` | Konten di atas dekorasi (`.cta > *`) |
| `50` | Navbar |
| `90` | Overlay gelap |
| `100` | Sidebar |
| `200` | Modal pop up |

Jangan memakai nilai di luar tabel ini. Elemen baru masuk ke urutan yang ada.

---

## 9. Motion & Transisi

| Durasi | Easing | Pakai untuk |
|---|---|---|
| `.15s` | default | Hover item sidebar |
| `.2s` | default | Hover tombol, link, nav-pill |
| `.25s` | default | Shadow navbar, overlay, opacity modal |
| `.3s` | `ease` | Geser sidebar / masuk-keluar modal |
| `.6s` | `ease` | Animasi reveal saat scroll |

- **Hover tombol standar:** `transform: translateY(-2px)` + `filter: brightness(.96)`.
- **Reveal scroll:** `translateY(18px)` → `0`, `opacity 0 → 1`, threshold `.15`.
- ❌ Dilarang durasi > `.6s` (terasa lambat) atau animasi tanpa `transition`.

---

## 10. Komponen Reusable

### 10.1 Wajib pakai sebelum menulis sendiri

| Kebutuhan | Pakai |
|---|---|
| Header navigasi | `../partials/navbar.php` |
| Menu mobile | `../partials/sidebar.php` |
| Footer | `../partials/footer.php` |
| Warna, container, tipografi dasar | `../assets/css/shared.css` |
| Buka/tutup sidebar & bayangan navbar | `../assets/js/shared.js` |

### 10.2 Contoh minimal sebuah fitur

```php
<!-- di <head> -->
<link rel="stylesheet" href="../assets/css/shared.css">
<link rel="stylesheet" href="style.css">

<!-- sebelum </body> -->
<script src="../assets/js/shared.js"></script>
<script src="script.js"></script>

<!-- buka body -->
<?php
$nav = [
    ['Home', '../landingPage/index.php', false],
    ['Food Database', 'index.php', true],     // hanya item INI yang aktif
    ['Recommendation', '#', false],
    ['Calculate Nutritions', '#', false],
];
include '../partials/navbar.php';
include '../partials/sidebar.php';
?>
<main> ... </main>
<?php include '../partials/footer.php'; ?>
```

Semua partial punya **default variabel** (`$nav`, `$isLoggedIn`, `$urlMasuk`, `$urlDaftar`, `$homeUrl`),
jadi aman di-include walau pemanggil tidak menyiapkan apa pun.

### 10.3 Saat menulis komponen baru

1. Dipakai ≥ 2 tempat → taruh di `partials/` + CSS-nya di `shared.css`.
2. Hanya untuk satu halaman → boleh lokal di folder fitur.
3. **Jangan** membuat komponen baru kalau varian yang sudah ada bisa dipakai
   (mis. cukup tambah class `.btn-outline`, bukan tombol baru).

---

## 11. Naming Convention

- **Kebab-case**, tanpa prefix namespace: `.nav-pill`, `.icon-box`, `.foot-links`, `.side-item`.
- **Modifier = class terpisah**, bukan elemen baru:
  ```html
  <a class="btn-sm btn-outline btn-logout">   <!-- kombinasi -->
  <div class="icon-box yellow">               <!-- warna = suffix -->
  <article class="card reveal">               <!-- perilaku = suffix -->
  ```
- Status/variant memakai **kata jelas**: `.active`, `.is-open`, `.scrolled`, `.on`, `.visible`.
- ID hanya untuk **hook JavaScript**: `#navbar`, `#menuBtn`, `#sidebar`, `#overlay`, `#authModal`.
- ❌ Dilarang BEM block (`block__elem--mod`) — proyek ini memakai **flat class + modifier**.
- ❌ Dilarang prefix fitur (`fd-card`, `lp-nav`) untuk komponen bersama.

---

## 12. Struktur & Urutan CSS

1. `:root` → reset → typography dasar → layout (`.container`) → komponen → `@media`.
2. **Urutan file:** `shared.css` dulu, `style.css` belakangan (agar bisa menimpa).
3. **Satu selector tidak boleh ada di dua file** — memecah cascade.
4. `@media` di **akhir** masing-masing file, urut: base → `768` → `1024`.
5. Spesifisitas tetap rendah (1 class) + state (`:hover`, `.active`) saja.
   Hindari `#id` di CSS; gunakan `#id` hanya untuk selektor JS.
6. Setiap file diawali komentar blok yang menjelaskan isi & cara pakai.

---

## 13. Aksesibilitas (wajib)

- Semua tombol ikon **punya `aria-label`** (`aria-label="Buka menu"`, `"Tutup"`, `"Tampilkan password"`).
- Menu mobile: `aria-controls`, `aria-expanded`, dan `aria-hidden` yang disinkronkan JS.
- Modal: `role="dialog"`, `aria-modal="true"`, `aria-hidden` saat tertutup.
- Gambar dekoratif: `alt=""`; gambar informatif: `alt` deskriptif.
- Teks di atas warna berjamin kontras: teks `#fff` hanya di atas `--green`.

---

## 14. Penyimpangan yang Sudah Ada — **wajib diperbaiki**

`foodDatabase/` dibuat sebelum aturan ini dan **belum selaras**. Saat menyentuh fitur itu,
ikuti kolom "Seharusnya":

| Aspek | ✅ Seharusnya (acuan landingPage) | ❌ foodDatabase sekarang |
|---|---|---|
| Warna primer | `#718a14` (`--green`) | `#728c28` (`--primary-olive`) |
| Primer gelap | `#5f7710` (`--green-dark`) | `#5c721e` |
| Background | `#fdf8f1` (`--cream`) | `#f7f4ed` (`--bg-cream`) |
| Teks utama | `#1c1c1a` (`--text`) | `#1f2414` (`--text-dark`) |
| Teks muted | `#55554f` (`--muted`) | `#707567` (`--text-muted`) |
| Nama variabel | `--green`, `--cream`, `--text` | `--primary-olive`, `--bg-cream`, `--text-dark` |
| Container | `max-width: 1280px` | `1140px` |
| Bobot font maks | `700` | `800` |
| Muat font | `<link>` di HTML | `@import` di CSS |
| Navbar/Footer | `partials/` + `shared.css` | markup & CSS sendiri |
| Radius elemen | `99px` pill untuk tombol/nav; permukaan `14`–`22px` | `8`–`20px` (tanpa pill), kontainer nav `30px` |

> Checklist di atas juga menjadi acuan saat **migrasi** `foodDatabase` ke design system.

---

## 15. Checklist sebelum Pull Request

- [ ] `<link shared.css>` ada dan **sebelum** `style.css`.
- [ ] `<script shared.js>` ada dan **sebelum** `script.js`.
- [ ] Navbar / Sidebar / Footer di-`include` dari `partials/`, bukan disalin.
- [ ] Tidak ada hex baru di luar §3 (terutama warna hijau/olive lain).
- [ ] Hanya `font-weight` 400/500/600/700 dan font Poppins.
- [ ] Ukuran teks mengikuti tabel §4.2 (input tetap `16px`).
- [ ] `container` memakai `max-width: 1280px` dari `shared.css`.
- [ ] Breakpoint hanya `768px` dan `1024px`.
- [ ] Radius tombol/pill `99px`.
- [ ] Shadow memakai keluarga yang benar (§7).
- [ ] Z-index masuk skala §8.
- [ ] Transisi ≤ `.6s`.
- [ ] Tidak ada `!important` baru, tidak ada BEM, tidak ada prefix fitur.
- [ ] Ikon tombol punya `aria-label`.
- [ ] Cek tampilan di **3 lebar layar**: mobile, `≥768px`, `≥1024px`.

---

## 16. Cara mengubah design system

Design system **tidak diubah diam-diam**. Kalau perlu token/nilai baru:

1. Buktikan nilainya dipakai di ≥ 2 tempat.
2. Tambahkan ke `rules.md` **dan** ke `:root` `shared.css` (kalau berupa token).
3. Tulis alasannya di deskripsi pull request.
4. Kalau berdampak ke fitur lain, sebutkan di PR description agar bisa ditinjau bersama.

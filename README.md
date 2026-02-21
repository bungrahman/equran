# eQuran - Technical Documentation

eQuran adalah solusi snippet PHP untuk WordPress yang memungkinkan integrasi daftar surat Al-Quran lengkap dengan fitur premium seperti tajwid berwarna, audio murattal multi-qari, dan tafsir digital. Dokumen ini merinci arsitektur teknis, API yang digunakan, dan metodologi pengembangan yang diimplementasikan.

Demo: [https://demo.jejakkreasi.com/equran/](https://demo.jejakkreasi.com/equran/)

## Arsitektur Sistem

Aplikasi ini menggunakan pendekatan hybrid yang menggabungkan rendering sisi server (Server-Side Rendering) untuk struktur utama dan interaksi sisi klien (Client-Side Interaction) untuk performa yang responsif.

### 1. Backend (PHP & WordPress Integration)
Metode utama yang digunakan adalah WordPress Shortcode API. Seluruh logika terkonsentrasi pada file `shortcode-function.php`.
- **Data Fetching**: Menggunakan `wp_remote_get` untuk pengambilan data dari API eksternal secara aman dan efisien di sisi server.
- **Output Buffering**: Menggunakan `ob_start()` dan `ob_get_clean()` untuk menangkap output HTML dan mengembalikannya secara bersih melalui shortcode.
- **Data Pre-processing**: Melakukan pengolahan data JSON serta konversi angka Barat ke numeral Arab (١, ٢, ٣) menggunakan fungsi `equran_to_arabic_number`.

### 2. Frontend (JavaScript & UI)
Interaksi dinamis dikelola sepenuhnya menggunakan Vanilla JavaScript (ES6+).
- **Asynchronous Operations**: Penggunaan `async/await` untuk pengambilan data surat, tajwid, dan tafsir secara paralel guna meminimalisir waktu tunggu.
- **Dynamic DOM Manipulation**: Memperbarui antarmuka pengguna secara real-time berdasarkan aksi pengguna (seperti pencarian surat atau pemilihan qari) tanpa memuat ulang halaman.
- **State Management**: Penyimpanan data tafsir secara lokal (`tafsirStore`) untuk akses cepat saat modal dibuka.

### 3. Arsitektur Styling
- **CSS Variables**: Menggunakan variabel CSS untuk manajemen warna primer (`--p-blue`) yang memungkinkan kustomisasi tema melalui parameter shortcode.
- **Typography Integration**: Integrasi Google Fonts (Amiri) sebagai standar tipografi Arab berkualitas tinggi dengan metode loading asinkron dan `font-display: swap` untuk optimasi User Experience.
- **Responsive Layout**: Implementasi CSS Grid dan Flexbox untuk memastikan tampilan presisi pada perangkat desktop maupun seluler.

## Rincian API yang Digunakan

Aplikasi ini mengintegrasikan dua penyedia API utama untuk menyajikan data Al-Quran yang komprehensif:

### 1. eQuran.id API (v2)
Berperan sebagai penyedia data utama Al-Quran.
- **Endpoint List Surat**: `https://equran.id/api/v2/surat`
- **Endpoint Detail Surat**: `https://equran.id/api/v2/surat/{nomor}`
- **Endpoint Tafsir**: `https://equran.id/api/v2/tafsir/{nomor}`
- **Data yang Diambil**: Teks Arab standar, teks Latin, terjemahan Bahasa Indonesia, audio murattal per ayat, dan audio full surat dari 6 qari internasional.

### 2. AlQuran.cloud Tajweed API
Digunakan khusus untuk sinkronisasi metadata tajwid.
- **Endpoint Surah**: `https://api.alquran.cloud/v1/surah/{nomor}/quran-tajweed`
- **Endpoint Ayah**: `https://api.alquran.cloud/v1/ayah/{surah}:{ayah}/quran-tajweed`
- **Metodologi**: API ini memberikan teks Arab yang disisipi marker tajwid (seperti `[f:3917]`). Teks ini kemudian diproses menggunakan Regular Expression di sisi klien untuk diubah menjadi elemen HTML `<span>` dengan kelas warna yang sesuai.

## Metodologi Implementasi Fitur

### 1. Parsing Tajwid Berwarna
Sistem menggunakan dua tahap parsing Regular Expression untuk menangani berbagai gaya penulisan tajwid:
- **Pass 1**: Menangani marker dengan format kurung ganda atau spesifikasi angka (`[f:123]`).
- **Pass 2**: Menangani marker dasar yang langsung menempel pada huruf untuk memastikan tidak ada aturan yang terlewat.
- **Dynamic Legend**: Sistem secara otomatis mengekstrak aturan unik yang ditemukan dalam satu ayat dan menampilkannya sebagai legenda di bawah teks Arab.

### 2. Penomoran Ayat Arab
Setiap akhir ayat dilengkapi dengan ornamen lingkaran yang berisi nomor ayat dalam numeral Arab.
- **Konversi Numerik**: Menggunakan pemetaan array sederhana dari angka 0-9 ke karakter Unicode Arab (٠-٩).
- **Styling**: Menggunakan CSS Border-Radius dan Flexbox untuk memastikan nomor ayat berada tepat di posisi tengah ornamen secara vertikal dan horizontal.

## Daftar Shortcode

| Shortcode | Deskripsi | Parameter Utama |
| :--- | :--- | :--- |
| `[tampilkan_quran]` | Daftar Al-Quran Lengkap (Dashboard) | `color` (Warna Tema) |
| `[equran_surat]` | Menampilkan Satu Surat Penuh | `nomor`, `color`, `audio` |
| `[equran_ayat]` | Menampilkan Satu Ayat Spesifik | `surat`, `ayat`, `color` |

## Instalasi

1. Salin file `shortcode-function.php` ke direktori tema WordPress Anda.
2. Tambahkan baris `require_once get_template_directory() . '/shortcode-function.php';` pada file `functions.php`.
3. Pastikan WordPress Anda memiliki akses internet untuk mengambil data dari endpoint API eksternal.

## Kontribusi

Pengembangan UI, optimasi algoritma parsing tajwid, serta penambahan fitur interaktif lainnya sangat disambut baik melalui mekanisme Pull Request pada repositori resmi.

## Lisensi

Proyek ini didistribusikan di bawah Lisensi MIT.

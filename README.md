# eQuran

eQuran adalah sebuah snippet fungsi PHP untuk menampilkan daftar surat Al-Quran lengkap dengan ayat, terjemahan Bahasa Indonesia, audio murattal dari 6 qari terbaik, dan tafsir di situs WordPress Anda menggunakan beberapa pilihan shortcode.

Demo: https://demo.jejakkreasi.com/equran/

## Teknologi yang Digunakan

- PHP: Logika backend untuk integrasi WordPress.
- WordPress Shortcode API: Menyediakan shortcode [tampilkan_quran], [equran_surat], dan [equran_ayat].
- eQuran.id API (v2): Layanan API eksternal yang menyediakan data Al-Quran lengkap dan audio berkualitas tinggi.
  - Endpoint Utama: https://equran.id/api/v2/surat
  - Endpoint Ayat & Tafsir: https://equran.id/api/v2/surat/{nomor} dan https://equran.id/api/v2/tafsir/{nomor}
- JavaScript (Async/Await): Untuk memuat data secara dinamis tanpa reload halaman.
- CSS Predefined Styles: Desain responsif yang meniru gaya antarmuka WordPress.
- Dashicons: Menggunakan library icon bawaan WordPress untuk antarmuka pengguna.

- **Al-Quran Digital**: Daftar surat lengkap dengan fitur pencarian real-time dan audio murattal.
- **Alkitab Digital (AYT)**: Baca teks Alkitab Bahasa Indonesia (AYT) lengkap dengan navigasi kitab dan pasal.
- **Fitur Pencarian**: Cari surat atau kitab dengan fitur filter instan.
- **Tampilan Responsif**: Layout grid yang optimal untuk desktop dan mobile selebaran.
- **Toolbar Premium**: Navigasi ayat/pasal, toggle transliterasi, dan terjemahan.
- **Audio Full (Quran)**: Sekali klik untuk memutar murottal 1 surat penuh.
- **Tafsir (Quran)**: Lihat tafsir lengkap setiap ayat dalam modal.
- **Kustomisasi**: Atribut warna dan audio untuk menyesuaikan gaya situs Anda.

## Daftar Qari (Audio)

Berikut adalah daftar qari yang tersedia beserta kodenya:
- 01: Abdullah Al-Juhany
- 02: Abdul Muhsin Al-Qasim
- 03: Abdurrahman As-Sudais
- 04: Ibrahim Al-Dossari
- 05: Misyari Rasyid Al-Afasi (Default)
- 06: Yasser Al-Dosari

## Instalasi

1. Download atau Clone:
   ```bash
   git clone https://github.com/bungrahman/equran.git
   ```

2. Integrasi ke Tema:
   Tambahkan kode dari shortcode_function.php ke file functions.php tema Anda, atau gunakan require_once:
   ```php
   require_once get_template_directory() . '/shortcode_function.php';
   ```

## Penggunaan

Gunakan shortcode berikut di halaman atau postingan WordPress Anda.

### 1. Daftar Al-Quran Lengkap
Shortcode ini menampilkan antarmuka utama dengan daftar surat dan pilihan qari.
```text
[tampilkan_quran color="WARNA"]
```
- color: (Opsional) Warna tema. Contoh: [tampilkan_quran color="blue"]

### 2. Menampilkan 1 Surat Penuh
Gunakan ini jika ingin menampilkan isi surat tertentu secara langsung beserta pemutar audio.
```text
[equran_surat nomor="X" color="WARNA" audio="KODE_QARI"]
```
- nomor: Nomor surat (1-114).
- color: (Opsional) Warna tema.
- audio: (Opsional) Kode qari (01-06).

Contoh: [equran_surat nomor="18" color="teal" audio="01"]

### 3. Menampilkan 1 Ayat Saja
Gunakan ini jika hanya ingin menampilkan satu ayat spesifik.
```text
[equran_ayat surat="X" ayat="Y" color="WARNA" audio="KODE_QARI"]
```
- surat: Nomor surat.
- ayat: Nomor ayat.
- color: (Opsional) Warna tema.
- audio: (Opsional) Kode qari (01-06).

Contoh: [equran_ayat surat="2" ayat="255" color="red" audio="03"]

### 4. Alkitab Digital Lengkap
Shortcode ini menampilkan antarmuka utama untuk membaca Alkitab (AYT).
```text
[tampilkan_alkitab color="WARNA"]
```
- color: (Opsional) Warna tema. Contoh: [tampilkan_alkitab color="purple"]

## Kontribusi

Bagi yang ingin berkontribusi dalam pengembangan UI atau fitur tambahan, silakan ajukan pull request.

## Lisensi

Proyek ini bersifat open-source di bawah Lisensi MIT.

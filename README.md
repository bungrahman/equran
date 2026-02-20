# eQuran

eQuran adalah sebuah snippet fungsi PHP untuk menampilkan daftar surat Al-Quran lengkap dengan ayat, terjemahan Bahasa Indonesia, audio murattal, dan tafsir di situs WordPress Anda menggunakan satu shortcode.

## Teknologi yang Digunakan

- **PHP**: Logika backend untuk integrasi WordPress.
- **WordPress Shortcode API**: Menyediakan shortcode `[tampilkan_quran]`.
- **eQuran.id API (v2)**: Layanan API eksternal yang menyediakan data Al-Quran lengkap.
  - Endpoint Utama: `https://equran.id/api/v2/surat`
  - Endpoint Ayat & Tafsir: `https://equran.id/api/v2/surat/{nomor}` dan `https://equran.id/api/v2/tafsir/{nomor}`
- **JavaScript (Async/Await)**: Untuk memuat data secara dinamis tanpa reload halaman.
- **CSS Predefined Styles**: Desain responsif yang meniru gaya antarmuka WordPress.
- **Dashicons**: Menggunakan library icon bawaan WordPress untuk antarmuka pengguna.

## Fitur

- **Daftar Surat**: Menampilkan seluruh surat dengan fitur pencarian real-time.
- **Mode Baca**: Tampilan per ayat dengan teks Arab yang indah.
- **Audio Murattal**: Mainkan audio per ayat secara langsung.
- **Tafsir**: Lihat tafsir lengkap untuk setiap ayat melalui modal pop-up.
- **Salin Ayat**: Fitur satu klik untuk menyalin teks Arab.

## Instalasi

1. **Download atau Clone**:
   ```bash
   git clone https://github.com/bungrahman/equran.git
   ```

2. **Integrasi ke Tema**:
   Tambahkan kode dari `shortcode_function.php` ke file `functions.php` tema Anda, atau gunakan `require_once`:
   ```php
   require_once get_template_directory() . '/shortcode_function.php';
   ```

## Penggunaan

Gunakan shortcode berikut di halaman atau postingan WordPress Anda:

```text
[tampilkan_quran]
```
## Demo 
https://demo.jejakkreasi.com/equran/

## Kontribusi

Bagi yang ingin berkontribusi dalam pengembangan UI atau fitur tambahan, silakan ajukan pull request.

## Lisensi

Proyek ini bersifat open-source di bawah Lisensi MIT.

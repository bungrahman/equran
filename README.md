# eQuran

eQuran adalah sebuah snippet fungsi PHP untuk menampilkan daftar surat Al-Quran lengkap dengan ayat, terjemahan Bahasa Indonesia, audio murattal, dan tafsir di situs WordPress Anda menggunakan satu shortcode.

Demo: https://demo.jejakkreasi.com/equran/

## Teknologi yang Digunakan

- PHP: Logika backend untuk integrasi WordPress.
- WordPress Shortcode API: Menyediakan shortcode [tampilkan_quran].
- eQuran.id API (v2): Layanan API eksternal yang menyediakan data Al-Quran lengkap.
  - Endpoint Utama: https://equran.id/api/v2/surat
  - Endpoint Ayat & Tafsir: https://equran.id/api/v2/surat/{nomor} dan https://equran.id/api/v2/tafsir/{nomor}
- JavaScript (Async/Await): Untuk memuat data secara dinamis tanpa reload halaman.
- CSS Predefined Styles: Desain responsif yang meniru gaya antarmuka WordPress.
- Dashicons: Menggunakan library icon bawaan WordPress untuk antarmuka pengguna.

## Fitur

- Daftar Surat: Menampilkan seluruh surat dengan fitur pencarian real-time.
- Mode Baca: Tampilan per ayat dengan teks Arab yang indah.
- Audio Murattal: Mainkan audio per ayat secara langsung.
- Tafsir: Lihat tafsir lengkap untuk setiap ayat melalui modal pop-up.
- Salin Ayat: Fitur satu klik untuk menyalin teks Arab.

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
Shortcode ini menampilkan antarmuka utama dengan daftar surat.
```text
[tampilkan_quran]
```

### 2. Menampilkan 1 Surat Penuh
Gunakan ini jika ingin menampilkan isi surat tertentu secara langsung.
```text
[equran_surat nomor="X" color="WARNA"]
```
- nomor: Nomor surat (1-114).
- color: (Opsional) Warna tema.

Contoh: [equran_surat nomor="18" color="teal"] (Surat Al-Kahfi)

### 3. Menampilkan 1 Ayat Saja
Gunakan ini jika hanya ingin menampilkan satu ayat spesifik.
```text
[equran_ayat surat="X" ayat="Y" color="WARNA"]
```
- surat: Nomor surat.
- ayat: Nomor ayat.
- color: (Opsional) Warna tema.

Contoh: [equran_ayat surat="2" ayat="255" color="red"] (Ayat Kursi)

### Kustomisasi Warna
Anda dapat mengubah warna tema pada semua shortcode di atas menggunakan atribut color. Anda bisa memasukkan nama warna dasar (red, green, blue, dll) atau kode warna Hex.

Contoh:
- [tampilkan_quran color="red"]
- [tampilkan_quran color="#27ae60"]

## Kontribusi

Bagi yang ingin berkontribusi dalam pengembangan UI atau fitur tambahan, silakan ajukan pull request.

## Lisensi

Proyek ini bersifat open-source di bawah Lisensi MIT.

# eQuran

eQuran adalah snippet fungsi PHP untuk menampilkan daftar surat Al-Quran lengkap dengan ayat, terjemahan Bahasa Indonesia, fitur tajwid berwarna, audio murattal dari 6 qari terbaik, dan tafsir di situs WordPress menggunakan shortcode.

Demo: [https://demo.jejakkreasi.com/equran/](https://demo.jejakkreasi.com/equran/)

## Teknologi yang Digunakan

- **PHP**: Logika backend untuk integrasi WordPress.
- **WordPress Shortcode API**: Menyediakan shortcode `[tampilkan_quran]`, `[equran_surat]`, dan `[equran_ayat]`.
- **Integrasi API**:
  - **eQuran.id API (v2)**: Memberikan data Al-Quran, terjemahan, dan audio.
  - **AlQuran.cloud Tajweed API**: Menyediakan metadata tajwid untuk sinkronisasi teks berwarna.
- **JavaScript (Async/Await)**: Memuat data secara dinamis tanpa reload halaman.
- **CSS Vanilla**: Desain responsif dengan integrasi font **Amiri** dari Google Fonts untuk tampilan Mushaf yang optimal.
- **Dashicons**: Menggunakan library icon bawaan WordPress.

## Fitur Utama

- **Tajwid Berwarna (17 Aturan)**: Mendukung pewarnaan teks Arab berdasarkan aturan tajwid resmi dengan sistem legend dinamis per ayat.
- **Tooltip Keterangan**: Legend tajwid dilengkapi dengan tooltip penjelasan aturan dalam Bahasa Indonesia.
- **Daftar Surat**: Menampilkan seluruh surat dengan fitur pencarian real-time dan pemutar audio surat lengkap.
- **Pilihan Qari**: Mendukung 6 qari internasional terbaik yang dapat dipilih secara global atau per surat.
- **Mode Baca Responsif**: Tampilan per ayat dengan font Arab berkualitas tinggi (Amiri) dan sinkronisasi audio.
- **Audio Murattal**: Mainkan audio per ayat atau satu surat penuh (audio full).
- **Tafsir Digital**: Akses tafsir lengkap untuk setiap ayat melalui modal pop-up yang informatif.
- **Salin & Berbagi**: Fitur untuk menyalin teks Arab langsung ke clipboard.

## Daftar Aturan Tajwid yang Didukung

Aplikasi ini mendukung sinkronisasi visual untuk 17 aturan tajwid berikut:

| Kode | Nama Tajwid | Deskripsi |
| :--- | :--- | :--- |
| `h` | Hamzatul Wasl | Penanda Washal Hamzah. |
| `s` | Saktah | Huruf yang tidak dibaca atau berhenti sejenak. |
| `l` | Lam Shamsiyyah | Aturan Al-Syamsiyah. |
| `n` | Mad Asli / Thabi'i | Pemanjangan normal: 2 Harakat. |
| `p` | Mad Jaiz Munfasil | Pemanjangan boleh: 2, 4, 6 Harakat. |
| `m` | Mad Lazim | Pemanjangan wajib: 6 Harakat. |
| `q` | Qalqalah | Bunyi pantulan pada huruf tertentu. |
| `o` | Mad Wajib Muttasil | Pemanjangan wajib: 4-5 Harakat. |
| `c` | Ikhfa Shafawi | Menyamarkan Mim Sakinah bertemu Ba. |
| `f` | Ikhfa | Menyamarkan Nun Sakinah atau Tanwin. |
| `w` | Idgham Shafawi | Meleburkan Mim Sakinah bertemu Mim. |
| `i` | Iqlab | Mengubah Nun Sakinah atau Tanwin menjadi Mim. |
| `a` | Idgham Bighunnah | Peleburan disertai suara dengung. |
| `u` | Idgham Bila Ghunnah | Peleburan tanpa suara dengung. |
| `d` | Idgham Mutajanisayn | Peleburan dua huruf yang sejenis. |
| `b` | Idgham Mutaqaribayn | Peleburan dua huruf makhraj berdekatan. |
| `g` | Ghunnah | Suara dengung selama 2 harakat. |

## Daftar Qari (Audio)

Tersedia audio murattal dari qari berikut:
- 01: Abdullah Al-Juhany
- 02: Abdul Muhsin Al-Qasim
- 03: Abdurrahman As-Sudais
- 04: Ibrahim Al-Dossari
- 05: Misyari Rasyid Al-Afasi (Default)
- 06: Yasser Al-Dosari

## Instalasi

### 1. Download atau Clone
```bash
git clone https://github.com/bungrahman/equran.git
```

### 2. Integrasi ke Tema WordPress
Tambahkan kode dari `shortcode-function.php` ke file `functions.php` tema Anda, atau gunakan `require_once`:
```php
require_once get_template_directory() . '/shortcode-function.php';
```

## Penggunaan Shortcode

### 1. Daftar Al-Quran Lengkap
Menampilkan antarmuka utama dengan daftar surat, pencarian, dan pilihan qari.
```text
[tampilkan_quran color="#hexcolor"]
```

<img width="911" height="766" alt="Desktop View" src="https://github.com/user-attachments/assets/564c182a-1791-418a-bdb2-b7d2a643583e" />
<img width="384" height="837" alt="Mobile View" src="https://github.com/user-attachments/assets/f4f236cf-f431-4db6-952e-1d54e39e4c95" />

### 2. Menampilkan Satu Surat Penuh
```text
[equran_surat nomor="X" color="#hexcolor" audio="KODE_QARI"]
```
- **nomor**: Nomor surat (1-114).
- **audio**: Kode qari (01-06).

### 3. Menampilkan Satu Ayat Spesifik
```text
[equran_ayat surat="X" ayat="Y" color="#hexcolor" audio="KODE_QARI"]
```

## Kontribusi

Bagi yang ingin berkontribusi dalam pengembangan UI, optimasi parsing tajwid, atau fitur tambahan, silakan ajukan pull request.

## Lisensi

Proyek ini bersifat open-source di bawah Lisensi MIT.

# eQuran

eQuran adalah sebuah snippet fungsi PHP sederhana untuk menampilkan ayat Al-Quran, terjemahan bahasa Indonesia, dan audio murattal dalam situs WordPress Anda menggunakan shortcode.

## Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi berikut:

- **PHP**: Bahasa pemrograman utama untuk logika fungsi.
- **WordPress Shortcode API**: Untuk membuat shortcode `[equran]` yang dapat digunakan di post atau page.
- **AlQuran Cloud API**: Layanan API eksternal untuk mengambil data ayat, terjemahan, dan audio.
  - Endpoint: `http://api.alquran.cloud/v1/ayah/{surah}:{ayah}/editions/quran-uthmani,id.indonesian`

## Fitur

- **Teks Arab**: Menggunakan font Scheherazade (jika tersedia) untuk keterbacaan yang baik.
- **Terjemahan Indonesia**: Menggunakan edisi `id.indonesian`.
- **Audio Murattal**: Pemutar audio HTML5 untuk mendengarkan bacaan ayat.
- **Styling Dasar**: Output sudah memiliki styling minimal agar terlihat rapi.

## Instalasi

1. **Download atau Clone**:
   ```bash
   git clone https://github.com/bungrahman/equran.git
   ```

2. **Integrasi ke Tema**:
   - Buka file `functions.php` di tema WordPress Anda.
   - Tambahkan kode dari `shortcode_function.php` ke dalamnya, atau;
   - Simpan `shortcode_function.php` di dalam folder tema, lalu panggil dengan:
     ```php
     require_once get_template_directory() . '/shortcode_function.php';
     ```

## Penggunaan

Gunakan shortcode berikut di dalam editor Post atau Page WordPress:

### Format Dasar
```text
[equran surah="NOMOR_SURAT" ayah="NOMOR_AYAT"]
```

### Contoh
Menampilkan Surat Al-Fatihah Ayat 1:
```text
[equran surah="1" ayah="1"]
```

Menampilkan Surat Al-Ikhlas Ayat 1:
```text
[equran surah="112" ayah="1"]
```

## Struktur Respons API

Kode ini mengambil data JSON dari AlQuran Cloud API dengan struktur berikut (disederhanakan):

```json
{
  "code": 200,
  "status": "OK",
  "data": [
    {
      "text": "بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ",
      "audio": "http://cdn.alquran.cloud/media/audio/ayah/ar.alafasy/1",
      "surah": { ... }
    },
    {
      "text": "Dengan menyebut nama Allah Yang Maha Pemurah lagi Maha Penyayang.",
      "edition": { "identifier": "id.indonesian" }
    }
  ]
}
```

## Lisensi

Proyek ini bersifat open-source dan tersedia di bawah Lisensi MIT.

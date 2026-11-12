# Cakrawala Auto Reload — Chrome Extension (Manifest V3)

Ekstensi Google Chrome resmi untuk keperluan pengujian otomatis (*testing*), pemantauan stabilitas reload halaman artikel, dan verifikasi fungsionalitas **View Counter** pada portal berita **Cakrawala Online**.

---

## 1. Fitur Utama

### A. URL Manager
- **Input URL Manual**: Mendukung penambahan URL artikel target secara mudah.
- **Ambil URL Tab Aktif (One-Click Grab)**: Otomatis mendeteksi dan mengambil URL dari tab Chrome yang sedang dibuka.
- **Multi-URL Queue**: Mendukung beberapa URL pengujian sekaligus secara bergantian.
- **Validasi Keamanan Ketat**: Hanya mengizinkan domain Cakrawala (`cakrawalaonline.com`, `www.cakrawalaonline.com`) dan lingkungan staging/lokal (`localhost`, `127.0.0.1`).

### B. Auto Reload Controller
- **Tombol Start, Pause, & Stop**: Kendali penuh atas siklus pengujian.
- **Pengaturan Interval**: Atur jeda waktu reload (minimal 2 detik, default 5 detik).
- **Target Jumlah Pengujian**: Batas maksimum siklus pengujian (contoh: 10, 25, atau 50 reload).
- **Indikator Live Status**: Status visual dengan denyut animasi (*Idle*, *Running*, *Paused*, *Stopped*, *Completed*, *Error*).
- **Pemberhentian Otomatis (Auto-Stop on Error)**: Pengujian otomatis dihentikan jika terdeteksi error pada halaman (404, 500, crash koneksi database, atau halaman kosong).

### C. View Counter Test Engine
- **Pembacaan Otomatis**: Membaca angka views pada footer artikel (`Dilihat: X kali` atau `X views`).
- **Analisis Delta (Sebelum vs Sesudah)**: Menghitung selisih pertambahan angka view counter secara transparan (+X Views).
- **Catatan Etika**: Dilengkapi peringatan etika bahwa pengujian ditujukan untuk verifikasi logika kode counter internal, bukan manipulasi statistik pembaca.

### D. Modern SaaS Dashboard UI
- **Desain Modern SaaS**: Menggunakan palet elegan dark/light mode dengan tipografi rapi.
- **Icon Lucide Murni**: Seluruh ikon menggunakan vektor SVG Lucide murni (tanpa ketergantungan CDN eksternal dan tanpa emoji).
- **Metrik Real-time**: Menampilkan Total URL, Sukses, Gagal, Durasi Waktu Berjalan (*stopwatch*), dan persentase progres.
- **Log Aktivitas Lengkap**: Riwayat peristiwa terperinci yang mencatat timestamp setiap reload dan status views.

---

## 2. Struktur File

```text
extension-cakrawala-autoreload/
├── manifest.json       # Konfigurasi Chrome Extension Manifest V3
├── popup.html          # Antarmuka Dashboard UI
├── popup.css           # Styling Modern SaaS (Light & Dark theme)
├── popup.js            # Controller antarmuka dan interaksi pengguna
├── background.js       # Service worker pengelola siklus reload & alarm
├── content.js          # Injected script pembaca counter & deteksi error
├── storage.js          # Modul Chrome Storage API (state, config, logs)
├── icons/              # Ikon ekstensi (16x16, 48x48, 128x128)
│   ├── icon16.png
│   ├── icon48.png
│   └── icon128.png
└── README.md           # Panduan lengkap instalasi & penggunaan
```

---

## 3. Cara Instalasi di Google Chrome

1. Buka browser **Google Chrome**.
2. Masuk ke halaman pengelolaan ekstensi dengan mengetikkan:
   ```text
   chrome://extensions/
   ```
   di *address bar* lalu tekan **Enter**.
3. Di pojok kanan atas, aktifkan toggle **Developer mode** (*Mode Pengembang*).
4. Klik tombol **Load unpacked** (*Muat yang belum dibongkar*) di pojok kiri atas.
5. Arahkan dan pilih folder berikut:
   ```text
   C:\Hai Motion\Cakrawala-newsportal\extension-cakrawala-autoreload
   ```
6. Ekstensi **Cakrawala Auto Reload** akan langsung terpasang dan siap digunakan!
7. Klik ikon *Puzzle* (Extensions) di toolbar Chrome, lalu sematkan (*Pin*) **Cakrawala Auto Reload** agar mudah diakses.

---

## 4. Cara Penggunaan

1. Buka halaman artikel Cakrawala yang ingin diuji (misalnya `https://cakrawalaonline.com/news-details.php?nid=597` atau di localhost `http://localhost:8000/news-details.php?nid=...`).
2. Klik ikon ekstensi **Cakrawala Auto Reload** pada toolbar browser untuk membuka popup dashboard.
3. Di tab **URL & Setelan**:
   - Klik **Ambil Tab Aktif** untuk memasukkan URL secara otomatis, atau ketikkan URL secara manual lalu klik **Tambah**.
   - Tentukan **Interval Reload** (contoh: 5 detik).
   - Tentukan **Target Pengujian** (contoh: 10 siklus).
4. Klik tombol hijau/merah **Mulai Pengujian (Start)**.
5. Ekstensi akan secara otomatis:
   - Membuka/memilih tab artikel.
   - Melakukan reload sesuai interval.
   - Membaca view counter sebelum dan sesudah reload.
   - Menghentikan proses saat target pengujian tercapai atau jika tombol **Stop** ditekan.
6. Buka tab **View Counter** untuk melihat perbandingan angka views awal, views akhir, dan perubahannya (+X).
7. Buka tab **Log Aktivitas** untuk meninjau riwayat teknis setiap reload.

---

## 5. Batasan & Keamanan

- Ekstensi ini **hanya beroperasi** pada domain `cakrawalaonline.com` dan `localhost/127.0.0.1`.
- Tidak melakukan bypass CAPTCHA atau mekanisme perlindungan keamanan server.
- Proses pengujian selalu dibatasi oleh jumlah siklus maksimum (*safe execution limit*) guna mencegah penggunaan resource berlebihan.


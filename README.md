<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Validasi Total Transaksi

Validasi `numeric` pada field `total` tidak cukup untuk mencegah manipulasi total transaksi. Aturan tersebut hanya memastikan bahwa nilai yang dikirim berupa angka, tetapi tidak memastikan bahwa jumlahnya benar. Pengguna masih bisa mengubah nilai total melalui browser sebelum mengirimkan form.

Untuk mencegah manipulasi, total transaksi harus dihitung ulang di sisi server berdasarkan harga produk yang tersimpan di database dan jumlah barang yang dibeli. Dengan begitu, server tidak langsung mempercayai nilai total yang dikirim dari form, sehingga hasil perhitungan transaksi lebih aman dan sesuai dengan data produk.


## Skenario Pengujian Manual

### 1. Pengujian Role Admin

**Langkah Pengujian:**

1. Buka halaman login `/login`.
2. Login menggunakan email `admin@pos.test` dan password `password`.
3. Buka halaman `/pos`, `/products`, `/transactions`, dan `/categories`.

**Hasil yang Diharapkan:**

- Admin berhasil login.
- Admin dapat mengakses halaman POS, produk, transaksi, dan kategori.

**Hasil Pengujian:**

- Halaman POS berhasil dibuka.
- Halaman produk berhasil dibuka, tetapi data produk masih kosong.
- Halaman kategori mengalami error View [categories.index] not found karena file tampilan kategori belum tersedia.
- Akses halaman transaksi admin perlu dipastikan melalui pengujian langsung.

### 2. Pengujian Role Kasir

**Langkah Pengujian:**

1. Logout dari akun sebelumnya.
2. Buka halaman login `/login`.
3. Login menggunakan email `kasir@pos.test` dan password `password`.
4. Buka halaman `/pos`.
5. Buka halaman `/transactions`.
6. Buka halaman `/products`.

**Hasil yang Diharapkan:**

- Kasir berhasil login dan dapat mengakses halaman POS.
- Kasir tidak dapat mengakses halaman transaksi, produk, dan kategori yang dibatasi untuk role lain.

**Hasil Pengujian:**

- Halaman POS berhasil dibuka.
- Akses `/transactions` ditolak dengan status 403 dan pesan bahwa halaman hanya untuk admin atau manager.
- Akses `/products` ditolak dengan status 403 dan pesan bahwa halaman hanya untuk admin.

### 3. Pengujian Role Manager

**Langkah Pengujian:**

1. Login menggunakan akun yang memiliki role `manager`.
2. Buka halaman `/transactions`.
3. Buka halaman `/products`.

**Hasil yang Diharapkan:**

- Manager berhasil login.
- Manager dapat mengakses halaman transaksi.
- Manager tidak dapat mengakses halaman produk.

**Status Pengujian:**

- Belum diuji secara langsung karena akun demo manager belum tersedia pada `DemoUserSeeder` yang diperiksa.

## Dokumentasi Praktikum Pertemuan 9: Import, Export, dan Queue

### 1. Diskusi Kelompok: Pemilihan Format Export untuk 100.000 Transaksi
Untuk laporan dengan skala **100.000 transaksi**, format yang paling tepat dipilih adalah **CSV (menggunakan `StreamedResponse` & `lazyById()`)**.

**Alasan:**
1. **Efisiensi Memori (Memory Footprint)**: Berbeda dengan format file terstruktur lainnya, data CSV dapat dialirkan baris demi baris secara streaming (`streamDownload` + `lazyById()`) langsung ke output buffer HTTP client. Server tidak perlu menampung seluruh 100.000 objek model ke dalam RAM, sehingga konsumsi memori tetap stabil dan tidak menabrak batas `memory_limit` PHP.
2. **Keterbatasan Format Excel (.xlsx)**: Pembuatan file `.xlsx` dengan 100.000 baris memerlukan perakitan file ZIP berisi XML terkompresi (OpenXML). Proses ini membutuhkan alokasi CPU dan RAM yang sangat besar, serta rentan mengalami *out-of-memory* atau *HTTP request timeout* bila diproses secara sinkron.
3. **Keterbatasan Format PDF**: DomPDF merender seluruh dokumen HTML menjadi representasi grafis di dalam memori sebelum menghasilkan berkas PDF. Men-generate ribuan halaman PDF sekaligus dari 100.000 transaksi hampir pasti memicu memory exhaustion (OOM) dan crash total. Oleh karena itu, ekspor PDF pada sistem ini sengaja dibatasi hanya untuk 50 transaksi terbaru.
4. **Interoperabilitas**: File CSV mudah diproses lebih lanjut oleh database engine, data pipeline, maupun aplikasi spreadsheet untuk analisis lanjutan.

---

### 2. Perbandingan Import Produk: Manual (PHP Native) vs Laravel Excel (Tugas Mandiri - Nadia Minatul Salma)
Berdasarkan implementasi import produk yang telah dibuat:
- **Mana yang Lebih Ringkas?**
  **Laravel Excel** lebih ringkas dalam hal struktur kode dasar. Dengan memanfaatkan antarmuka `ToModel`, `WithHeadingRow`, dan `WithChunkReading`, developer tidak perlu menulis fungsi manual parsing file (`fopen`, `fgetcsv`, `str_getcsv`, `fclose`), pembacaan baris pertama sebagai header (`array_shift`, `array_combine`), maupun pemecahan data ke dalam chunk. Semua abstraksi tersebut ditangani otomatis oleh library.
- **Mana yang Lebih Mudah Dikontrol?**
  **Versi Manual (PHP Native / Custom Queue)** jauh lebih mudah dikontrol secara fleksibel dan granular:
  1. *Kontrol Encoding & Delimiter*: Penanganan masalah format CSV lokal (seperti titik koma `;`, pembersihan UTF-8 BOM, dan karakter khusus) dapat dilakukan langsung tanpa ketergantungan konfigurasi library.
  2. *Pencatatan Error Baris-per-Baris*: Pada versi manual, developer dapat secara presisi melacak nomor baris (`$rowNumber`) dan pesan validasi spesifik ke database/tabel `import_errors` tanpa menghentikan iterasi baris lainnya.
  3. *Integrasi Background Queue*: File CSV dapat langsung disimpan ke disk (`Storage::disk`), dan path-nya dikirim ke job antrean (`ProcessProductImport`) dengan payload pesan database yang sangat ringan, menjaga ketahanan aplikasi dan pemakaian resource server.

---

### 3. Identifikasi Proses Simple POS yang Layak Dipindah ke Queued Job (Tugas Mandiri - Yosep Bima Aprillian)
- **Proses yang Diidentifikasi**: **Pengiriman Email Struk Transaksi Digital kepada Pelanggan (`SendReceiptEmailJob`)**.
- **Alasan Pemilihan**:
  1. **Mengeliminasi Bottleneck I/O Jaringan**: Komunikasi dengan server SMTP / mail provider eksternal (seperti Mailgun, Amazon SES, dsb.) membutuhkan waktu handshake dan transfer jaringan berkisar antara 1 hingga 3 detik. Jika proses ini dijalankan secara sinkron saat kasir menekan tombol pembayaran, kasir dan pembeli harus menunggu loading browser selesai, yang akan memperlambat antrean kasir.
  2. **Pengalaman Pengguna (UX) yang Cepat**: Dengan memindahkan pengiriman email ke queued job, controller kasir dapat segera menyimpan data transaksi, mengurangi stok, mencetak struk fisik, dan seketika mengembalikan respons sukses ("Transaksi Berhasil") kepada kasir tanpa hambatan.
  3. **Ketahanan terhadap Kegagalan (Fault Tolerance & Retry Mechanism)**: Layanan mail gateway rentan mengalami gangguan sementara (rate limit, timeout jaringan). Melalui konfigurasi queue dengan `$tries = 3` dan `$backoff = [10, 60]`, kegagalan pengiriman email tidak akan membatalkan transaksi keuangan yang sudah sah, melainkan akan otomatis dicoba kembali di latar belakang. Jika tetap gagal setelah batas percobaan, error akan tercatat di tabel `failed_jobs` sehingga dapat dipantau dan di-retry oleh teknisi sistem.


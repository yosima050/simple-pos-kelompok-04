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

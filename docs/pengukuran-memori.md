# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data | Peak memory (MB) |
|---|---|
| `Transaction::lazyById()` | 53.0 |
| `Transaction::get()` | 111.4 |

Kesimpulan kelompok:
Penggunaan `get()` memuat seluruh 52.500 objek model Eloquent sekaligus ke dalam memori RAM aplikasi (array of models), sehingga menyebabkan lonjakan konsumsi memori puncak (peak memory) yang sangat tinggi mencapai 111.4 MB. Jika data transaksi terus bertambah besar, metode `get()` berisiko tinggi menabrak batas `memory_limit` pada konfigurasi PHP server dan menyebabkan aplikasi crash (Fatal Error: Allowed memory size exhausted).

Sebaliknya, `Transaction::lazyById()` memanfaatkan fitur `LazyCollection` di Laravel yang mengambil data dari database secara bertahap dalam chunk kecil berurutan berdasarkan ID. Setiap chunk yang selesai diproses langsung dibebaskan memorinya oleh garbage collector PHP, sehingga konsumsi memori puncak tetap datar dan rendah (53.0 MB). Dengan demikian, untuk proses reporting, export file, atau pengolahan dataset besar, `lazyById()` terbukti jauh lebih efisien, aman terhadap batasan memori, dan scalable.


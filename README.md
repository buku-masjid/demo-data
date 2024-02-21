# Buku Masjid Demo Data Generator

## Tentang Pustaka

Pustaka ini adalah paket composer yang digunakan untuk meng-generate data dummy untuk kebutuhan simulasi. Paket ini hanya berfungsi untuk proyek [Buku Masjid](https://github.com/buku-masjid/buku-masjid) saja.

## Cara Install

Pada dasarnya, paket ini sudah ter-install sebagai dev-dependency dari proyek Buku Masjid. Tetapi jika ingin menginstallnya, lakukan langkah berikut: 

```bash
$ composer require buku-masjid/demo-data --dev
```

## Cara Pakai

Generate demo data (3 bulan terakhir):

```bash
$ php artisan buku-masjid:generate-demo-data
```

Generate demo data dengan me-reset seluruh isi database.

```bash
$ php artisan buku-masjid:generate-demo-data --reset-all
```

Generate demo data dengan rentang tanggal tertentu.

```bash
$ php artisan buku-masjid:generate-demo-data --start_date=2023-07-01 --end_date=2023-10-31
```

Hapus semua demo data (yang `created_at` nya `NULL`)

```bash
$ php artisan buku-masjid:remove-demo-data
```

## Lisensi

Paket ini merupakan perangkat pustaka open-source yang dilisensikan di bawah [Lisensi MIT](LICENSE).

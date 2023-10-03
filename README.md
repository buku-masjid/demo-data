# Buku Masjid Demo Data Generator

## Tentang Pustaka

Pustaka ini adalah paket composer yang digunakan untuk meng-generate data dummy untuk kebutuhan simulasi. Paket ini hanya berfungsi untuk proyek [Buku Masjid](https://github.com/buku-masjid/buku-masjid) saja.

## Cara Install

Pada dasarnya, paket ini sudah ter-install sebagai dev-dependency dari proyek Buku Masjid. Tetapi jika ingin menginstallnya, lakukan langkah berikut: 

```bash
$ composer require buku-masjid/demo-data --dev
```

## Cara Pakai

Generate demo data:

```bash
$ php artisan buku-masjid:demo-data
```

Generate demo data dengan me-reset seluruh isi database.

```bash
$ php artisan buku-masjid:demo-data --reset-all
```

## Lisensi

Paket ini merupakan perangkat pustaka open-source yang dilisensikan di bawah [Lisensi MIT](LICENSE).

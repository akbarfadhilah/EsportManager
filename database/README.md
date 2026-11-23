# Database MySQL

Database aplikasi menggunakan MySQL/MariaDB dengan nama `esportmanagervol1`.
Seeder `RegionSeeder` mendaftarkan 18 negara beserta benderanya dari
`public/Bendera`; kolom ketua persatuan game dan pelatih timnas dibiarkan kosong.

## Menyiapkan MySQL pada komputer baru

Buat database `esportmanagervol1` di phpMyAdmin, lalu atur `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=esportmanagervol1
DB_USERNAME=root
DB_PASSWORD=
```

Pilih salah satu cara untuk mengisi database.

### Memulihkan dari SQL

Pilih database `esportmanagervol1` di phpMyAdmin lalu impor
`database/esportmanagervol1.sql`. Dump mencakup skema dan data aplikasi, tetapi
tidak menyalin sesi login yang sementara.

### Membuat database baru dengan migrasi

```powershell
composer install
php artisan migrate
php artisan db:seed --class=RegionSeeder
```

Salin juga isi `public/Bendera` saat memindahkan proyek agar file bendera tersedia.

## Import atlet

Menu **Import dari Excel** menerima file `.csv` dan `.xlsx` (maksimal 5 MB,
500 baris data). Baris pertama menjadi judul kolom; kolom berikutnya dicocokkan
di layar pemetaan. Data baru masuk ke database setelah seluruh baris lolos
validasi dan dikonfirmasi dari layar preview.

Setiap atlet wajib memiliki nama asli, nickname, tanggal lahir berformat
`DD/MM/YYYY`, region yang sudah terdaftar, role utama, dua gaya permainan, dan
tiga urutan targeting. Role cadangan pertama/kedua serta deskripsi boleh kosong.
Gunakan nilai yang tersedia di form atlet untuk role, gaya permainan, dan
targeting; role kurang kompeten dihitung otomatis saat penyimpanan.

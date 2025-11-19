# Cara Menjalankan Default Film Seeder

## Opsi 1: Menggunakan Laravel Seeder (Recommended)

1. **Start MySQL/XAMPP**
   - Buka XAMPP Control Panel
   - Klik tombol "Start" untuk MySQL
   - Pastikan status berubah jadi hijau (running)

2. **Jalankan Seeder**
   ```bash
   php artisan db:seed --class=DefaultFilmSeeder
   ```

3. **Verifikasi Data**
   ```bash
   # Cek jumlah film
   php artisan tinker
   Film::count()
   
   # Cek jumlah tiket untuk film pertama
   Ticket::where('film_id', 1)->count()
   # Harus return: 134
   ```

## Opsi 2: Menggunakan SQL Manual

1. **Start MySQL/XAMPP** (sama seperti Opsi 1)

2. **Buka phpMyAdmin**
   - URL: http://localhost/phpmyadmin
   - Pilih database `wbf`

3. **Import SQL**
   - Klik tab "SQL"
   - Copy paste isi file `default_film.sql`
   - Klik "Go" untuk execute

## Troubleshooting

### Error: "Target machine actively refused"
- **Penyebab**: MySQL belum jalan
- **Solusi**: Start MySQL via XAMPP Control Panel

### Error: "Database 'wbf' doesn't exist"
- **Penyebab**: Database belum dibuat
- **Solusi**: 
  ```bash
  # Buat database dulu
  php artisan migrate
  ```

### Error: "Class 'DefaultFilmSeeder' not found"
- **Penyebab**: Composer autoload belum refresh
- **Solusi**:
  ```bash
  composer dump-autoload
  php artisan db:seed --class=DefaultFilmSeeder
  ```

## Detail Film yang Di-seed

- **Judul**: Jujutsu Kaisen Movie Shibuya Incident x Culling Game
- **Harga**: Rp 50,000
- **Poster**: images/poster1.jpg
- **Total Kursi**: 134 (17 unit di baris A termasuk 3 couple set)
- **Layout**:
  - Baris J-B: 14 kursi per baris (8 baris)
  - Baris A: 14 kursi reguler + 3 couple set
    - A1 (reguler)
    - Couple Set A3 (A2-A3)
    - Couple Set A2 (A4-A5)
    - A6-A18 (reguler)
    - Couple Set A1 (A19-A20)

## Catatan Penting

⚠️ **Poster Image**: Pastikan file `public/images/poster1.jpg` sudah ada. Jika belum, tambahkan gambar poster atau ganti nama file di seeder.

✅ **File Seeder**: `database/seeders/DefaultFilmSeeder.php` sudah siap digunakan.

✅ **File SQL Backup**: `database/seeders/default_film.sql` tersedia sebagai alternatif.

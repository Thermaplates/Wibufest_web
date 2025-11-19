# Railway Deployment Guide

## Penyebab Error 500 di Railway

1. **APP_KEY tidak diset** - Laravel membutuhkan APP_KEY untuk enkripsi
2. **Database tidak terkonfigurasi** - Perlu tambah MySQL service di Railway
3. **Environment variables tidak lengkap**
4. **Storage directory permission** - Laravel butuh akses write ke storage/

## Setup Railway

### 1. Add MySQL Database
Di Railway dashboard:
- Klik "New" → "Database" → "Add MySQL"
- Railway akan auto-generate: `MYSQL_URL`, `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DATABASE`

### 2. Set Environment Variables
Di Railway dashboard → Variables tab, tambahkan:

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:bzVNUaGsJLEcdJU7OhNsu+LJAspIkiZ711f8pFZxL4U=
APP_URL=https://your-app.railway.app

# Database (otomatis dari MySQL service)
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_DATABASE}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

# Admin
ADMIN_PASSWORD=gedebongpisanghitam

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
```

### 3. Generate New APP_KEY (Opsional)
Jika ingin generate key baru:
```bash
php artisan key:generate --show
```
Copy hasilnya ke Railway variables.

### 4. Deploy
```bash
git add .
git commit -m "Fix Railway deployment configuration"
git push origin main
```

Railway akan auto-deploy dari GitHub.

## Troubleshooting

### Cek Logs
Di Railway dashboard → "Deployments" → klik deployment terakhir → "View Logs"

### Common Errors:

**"No application encryption key has been specified"**
- Solusi: Set `APP_KEY` di Railway variables

**"SQLSTATE[HY000] [2002] Connection refused"**
- Solusi: Pastikan MySQL service sudah ditambahkan dan variabel DB_* sudah diset

**"The stream or file could not be opened"**
- Solusi: Sudah diatasi dengan `php artisan storage:link || true` di railway.toml

**"Route not found"**
- Solusi: Cache sudah dibersihkan dengan `php artisan config:cache` dan `php artisan route:cache`

## File Configuration

✅ `railway.toml` - Sudah diupdate dengan cache commands
✅ `.env.example` - Sudah diupdate dengan production settings
✅ Database migrations - Ready untuk `php artisan migrate --force`

## Post-Deployment

Setelah deploy berhasil, jalankan seeder untuk data default:

1. Install Railway CLI:
   ```bash
   npm i -g @railway/cli
   ```

2. Login dan link project:
   ```bash
   railway login
   railway link
   ```

3. Run seeder:
   ```bash
   railway run php artisan db:seed --class=DefaultFilmSeeder
   ```

Atau alternatif: Import `database/seeders/default_film.sql` via phpMyAdmin di MySQL Railway.

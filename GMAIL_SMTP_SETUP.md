# Setup Gmail SMTP untuk Railway

## 1. Buat App Password Gmail

1. Buka https://myaccount.google.com/security
2. Aktifkan **2-Step Verification** (wajib)
3. Setelah aktif, cari menu **App passwords**
4. Pilih aplikasi: **Mail**
5. Pilih device: **Other** → Ketik "Wibufest"
6. Copy 16-digit password yang dihasilkan

## 2. Konfigurasi Railway Variables

Login ke Railway Dashboard → Pilih Project Wibufest → Variables

Tambahkan variable berikut:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=Wibufest
```

**Penting:**
- `MAIL_USERNAME` = Email Gmail lengkap (contoh: wibufest@gmail.com)
- `MAIL_PASSWORD` = 16-digit App Password (BUKAN password Gmail biasa)
- `MAIL_FROM_ADDRESS` = Harus sama dengan MAIL_USERNAME
- Jangan gunakan spasi dalam password

## 3. Test Fitur Email

1. Login ke admin dashboard
2. Cari booking yang ingin dikirim tiket
3. Klik tombol hijau **"Kirim Tiket"**
4. Sistem akan kirim email ke email pembooking
5. Check inbox pembooking (check juga folder Spam)

## 4. Troubleshooting

### Email tidak terkirim:
- Pastikan App Password sudah benar
- Pastikan 2-Step Verification aktif di Gmail
- Check Railway logs: `railway logs`
- Cek error di Laravel log

### Email masuk Spam:
- Normal untuk email pertama kali
- Minta penerima mark "Not Spam"
- Gunakan custom domain untuk produksi (opsional)

### Rate Limit Gmail:
- Gmail limit: 500 email/hari untuk free account
- Limit per batch: 100 recipients
- Jeda antar email: minimal 1 detik

## 5. Local Development

Update `.env` lokal:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=Wibufest
```

Test dengan Artisan Tinker:

```bash
php artisan tinker

# Test kirim email
$booking = App\Models\Booking::first();
Mail::to('test@example.com')->send(new App\Mail\BookingTicketMail($booking));
```

## 6. Email Template

Email yang dikirim berisi:
- ✅ Header Wibufest dengan logo
- ✅ ID Booking
- ✅ Nama pembooking
- ✅ Email pembooking
- ✅ Judul film (jika ada)
- ✅ Nomor kursi (badge besar)
- ✅ Catatan penting check-in
- ✅ Footer profesional

Email template: `resources/views/emails/booking-ticket.blade.php`

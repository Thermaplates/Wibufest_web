<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Booking Wibufest</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #ef4444;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            background: linear-gradient(135deg, #ef4444 0%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        .title {
            color: #374151;
            font-size: 20px;
            margin: 0;
        }
        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .info-box {
            background: #f9fafb;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
        }
        .seats {
            background: linear-gradient(135deg, #ef4444 0%, #ec4899 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
        }
        .note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 14px;
            color: #92400e;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .info-row {
                flex-direction: column;
            }
            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo"> Wibufest Jogja</div>
            <h2 class="title">Konfirmasi Booking Tiket</h2>
        </div>

        <p class="greeting">Halo, <strong>{{ $booking->name }}</strong>! 👋</p>
        
        <p>Terima kasih telah melakukan booking di<strong> Wiftix</strong>. Berikut adalah detail booking Anda:</p>

        <div class="info-box">
            <div class="info-row">
                <span class="info-label">ID Booking:</span>
                <span class="info-value">#{{ $booking->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama:</span>
                <span class="info-value">{{ $booking->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $booking->email }}</span>
            </div>
            @if($booking->film)
            <div class="info-row">
                <span class="info-label">Film:</span>
                <span class="info-value">{{ $booking->film->title }}</span>
            </div>
            @endif
        </div>

        <div class="seats">
            📍 Nomor Kursi: {{ $seats }}
        </div>

        <div class="note">
            <strong>⚠️ Catatan Penting:</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                <li>Simpan email ini sebagai bukti booking Anda</li>
                <li>Tunjukkan ID booking (#{{ $booking->id }}) saat check-in</li>
                <li>Datang 15 menit sebelum acara dimulai</li>
                <li>Jangan lupa bawa bukti pembayaran</li>
            </ul>
        </div>

        <p style="text-align: center; margin: 30px 0;">
            Sampai jumpa! 🎉
        </p>

        <div class="footer">
            <p style="margin: 5px 0;"><strong>Wibufest Jogja Production</strong></p>
            <p style="margin: 5px 0;">Email ini dikirim otomatis, mohon tidak membalas.</p>
            <p style="margin: 5px 0; color: #9ca3af;">© 2025 Wibufest. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun SIMBARA - Kredensial Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .credentials-box {
            background: white;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .credential-label {
            font-weight: bold;
            color: #495057;
        }
        .credential-value {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            font-family: monospace;
        }
        .login-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Selamat! Akun SIMBARA Anda Telah Aktif</h1>
                                <p>Sistem Informasi Manajemen Barang FMIPA UNUD</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $nama }}</strong>,</p>

        <p>Selamat! Pengajuan pendaftaran Anda untuk kegiatan <strong>{{ $kegiatan }}</strong> di sistem SIMBARA telah <strong>disetujui</strong> dan akun Anda telah berhasil dibuat.</p>

        <div class="credentials-box">
            <h3 style="color: #28a745; margin-top: 0;">🔑 Kredensial Login Anda</h3>
            
            <div class="credential-item">
                <div class="credential-label">Username:</div>
                <div class="credential-value">{{ $username }}</div>
            </div>
            
            <div class="credential-item">
                <div class="credential-label">Password:</div>
                <div class="credential-value">{{ $password }}</div>
            </div>
        </div>

        <div class="warning">
            <strong>⚠️ Penting:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Simpan kredensial ini dengan aman</li>
                <li>Jangan bagikan username dan password kepada orang lain</li>
                <li>Segera ganti password setelah login pertama</li>
                <li>Akun Anda akan berakhir pada: <strong>{{ \Carbon\Carbon::parse($expiry)->format('d F Y') }}</strong></li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="login-button">
                🚀 Login ke SIMBARA
            </a>
        </div>

        <h3>📋 Langkah Selanjutnya:</h3>
        <ol>
            <li><strong>Login</strong> menggunakan kredensial di atas</li>
            <li><strong>Lengkapi profil</strong> Anda jika diperlukan</li>
            <li><strong>Jelajahi katalog</strong> barang yang tersedia</li>
            <li><strong>Mulai melakukan peminjaman</strong> sesuai kebutuhan</li>
        </ol>

        <h3>🛡️ Keamanan Akun:</h3>
        <ul>
            <li>Selalu logout setelah selesai menggunakan sistem</li>
            <li>Gunakan password yang kuat dan unik</li>
            <li>Laporkan jika ada aktivitas mencurigakan pada akun Anda</li>
        </ul>

        <h3>📞 Butuh Bantuan?</h3>
        <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi kami:</p>
        <ul>
                                        <li>📧 Email: simbara@fmipa.unud.ac.id</li>
                                        <li>📱 Telepon: (0361) 701954</li>
            <li>🕐 Jam Kerja: Senin-Jumat, 08:00-16:00 WIB</li>
        </ul>

        <p>Terima kasih telah bergabung dengan SIMBARA. Semoga sistem ini dapat membantu kebutuhan peminjaman barang Anda!</p>

        <p>Salam,<br>
        <strong>Tim SIMBARA</strong><br>
        Fakultas Matematika dan Ilmu Pengetahuan Alam<br>
        Universitas Padjadjaran</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh sistem SIMBARA. Mohon tidak membalas email ini.</p>
                                <p>© {{ date('Y') }} FMIPA UNUD. Semua hak cipta dilindungi.</p>
    </div>
</body>
</html> 
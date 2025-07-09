<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMBARA - Pengajuan Pendaftaran</title>
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
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
        .reason-box {
            background: white;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .reapply-button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
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
        <h1>📋 Pengajuan Pendaftaran SIMBARA</h1>
                                <p>Sistem Informasi Manajemen Barang FMIPA UNUD</p>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $pengajuan->nama_penanggung_jawab }}</strong>,</p>

        <p>Terima kasih atas pengajuan pendaftaran Anda di sistem SIMBARA yang telah dikirim pada <strong>{{ $pengajuan->tanggal_pengajuan->format('d F Y') }}</strong>.</p>

        <p>Setelah melakukan verifikasi dan evaluasi terhadap dokumen yang Anda kirimkan, dengan berat hati kami harus menginformasikan bahwa pengajuan pendaftaran Anda <strong>belum dapat disetujui</strong> pada saat ini.</p>

        <div class="reason-box">
            <h3 style="color: #dc3545; margin-top: 0;">📝 Alasan Penolakan:</h3>
            <p style="margin: 0; font-size: 14px; line-height: 1.5;">
                {{ $reason }}
            </p>
        </div>

        <div class="info-box">
            <strong>💡 Informasi Penting:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Penolakan ini bukan berarti Anda tidak dapat mendaftar kembali</li>
                <li>Anda dapat memperbaiki dokumen/data sesuai dengan alasan penolakan</li>
                <li>Silakan ajukan pendaftaran ulang setelah memperbaiki kekurangan</li>
                <li>Pastikan semua dokumen lengkap dan sesuai dengan persyaratan</li>
            </ul>
        </div>

        <h3>📋 Langkah Selanjutnya:</h3>
        <ol>
            <li><strong>Perbaiki</strong> dokumen atau data sesuai dengan alasan penolakan di atas</li>
            <li><strong>Pastikan</strong> semua persyaratan telah dipenuhi</li>
            <li><strong>Ajukan</strong> pendaftaran ulang melalui sistem</li>
            <li><strong>Tunggu</strong> proses verifikasi ulang dari tim kami</li>
        </ol>

        <h3>📄 Persyaratan Pendaftaran:</h3>
        <ul>
            <li><strong>Untuk Civitas Akademik:</strong> Surat keterangan dari fakultas/jurusan yang masih berlaku</li>
            <li><strong>Untuk Non-Civitas:</strong> Surat keterangan dari organisasi/instansi yang jelas dan resmi</li>
            <li><strong>Data Pribadi:</strong> Lengkap dan akurat (nama, email, nomor HP, nomor identitas)</li>
            <li><strong>Tujuan Peminjaman:</strong> Jelas dan spesifik</li>
        </ul>

        <div style="text-align: center;">
            <a href="{{ route('pendaftaran.create') }}" class="reapply-button">
                🔄 Daftar Ulang
            </a>
        </div>

        <h3>📞 Butuh Bantuan?</h3>
        <p>Jika Anda memiliki pertanyaan atau memerlukan klarifikasi lebih lanjut mengenai penolakan ini, jangan ragu untuk menghubungi kami:</p>
        <ul>
                                        <li>📧 Email: simbara@fmipa.unud.ac.id</li>
                                        <li>📱 Telepon: (0361) 701954</li>
            <li>🕐 Jam Kerja: Senin-Jumat, 08:00-16:00 WIB</li>
        </ul>

        <p>Kami berharap Anda dapat memperbaiki kekurangan yang ada dan mengajukan pendaftaran ulang. Tim kami siap membantu Anda dalam proses pendaftaran.</p>

        <p>Terima kasih atas pengertian dan kerjasamanya.</p>

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
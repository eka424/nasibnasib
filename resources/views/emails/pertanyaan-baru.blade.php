<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pertanyaan Baru</title>
</head>
<body style="font-family:Arial, sans-serif; color:#111; line-height:1.5;">
    <h2 style="margin:0 0 12px 0;">Ada pertanyaan baru</h2>
    <p style="margin:0 0 8px 0;"><strong>Kategori:</strong> {{ $kategoriLabel }}</p>
    <p style="margin:0 0 8px 0;"><strong>Penanya:</strong> {{ $penanya->name ?? 'Pengguna' }} ({{ $penanya->email ?? '-' }})</p>
    <p style="margin:0 0 8px 0;"><strong>Pertanyaan:</strong></p>
    <p style="margin:0 0 14px 0;">{{ $pertanyaan->pertanyaan }}</p>
    <p style="margin:0 0 6px 0;">
        <a href="{{ $linkModerasi }}" style="display:inline-block; background:#10b981; color:#fff; padding:10px 16px; text-decoration:none; border-radius:6px;">Buka Moderasi</a>
    </p>
    <p style="margin:12px 0 0 0; color:#555; font-size:12px;">Pesan otomatis dari aplikasi masjid.</p>
</body>
</html>

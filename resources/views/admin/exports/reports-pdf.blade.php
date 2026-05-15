<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kerusakan Infrastruktur Desa</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        body { margin: 0; padding: 20px; color: #1e293b; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #2563eb; margin: 0; }
        .header p { color: #64748b; margin: 4px 0 0; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .meta p { color: #64748b; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #2563eb; color: white; padding: 7px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tr:nth-child(even) td { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 20px; font-size: 8px; font-weight: bold; }
        .pending   { background: #fef9c3; color: #854d0e; }
        .process   { background: #dbeafe; color: #1e40af; }
        .completed { background: #dcfce7; color: #15803d; }
        .rejected  { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; text-align: center; color: #94a3b8; font-size: 8px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏛️ Sistem Informasi Pelaporan Kerusakan Infrastruktur Desa</h1>
        <p>Laporan diekspor pada {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>
    <div class="meta">
        <p>Total: <strong>{{ $reports->count() }} laporan</strong></p>
        <p>Selesai: <strong>{{ $reports->where('status','completed')->count() }} laporan</strong></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Judul</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $r->code }}</strong></td>
                <td>{{ $r->title }}</td>
                <td>{{ $r->user->name }}</td>
                <td>{{ $r->category->name }}</td>
                <td>{{ Str::limit($r->location, 30) }}</td>
                <td><span class="badge {{ $r->status }}">{{ $r->status_label }}</span></td>
                <td>{{ $r->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>© {{ date('Y') }} Sistem Pengaduan Infrastruktur Desa — Dokumen ini dibuat otomatis oleh sistem</p>
    </div>
</body>
</html>

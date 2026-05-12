<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Parkir</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; color: #1E293B; }
        .header p { margin: 5px 0 0 0; color: #64748B; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #CBD5E1; padding: 8px; text-align: left; }
        th { background-color: #F1F5F9; color: #334155; font-size: 11px; text-transform: uppercase; }
        .badge { color: #10B981; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Sistem Prediksi Parkir PCR</h2>
        <p>Data Riwayat Pencarian Berdasarkan Filter</p>
        <p style="font-size: 10px;">Dicetak pada: {{ date('d M Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Pencarian</th>
                <th>Nama Pengguna</th>
                <th>Email</th>
                <th>Input Jadwal</th>
                <th>Lokasi Rekomendasi</th>
                <th>Kendaraan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->user->name ?? 'Guest / Anonim' }}</td>
                <td>{{ $item->user->email ?? '-' }}</td>
                <td>{{ $item->hari }}, {{ \Carbon\Carbon::parse($item->jam)->format('H:i') }}</td>
                <td class="badge">{{ $item->lokasi }}</td>
                <td style="text-transform: capitalize;">{{ $item->jenis_kendaraan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data pada filter ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

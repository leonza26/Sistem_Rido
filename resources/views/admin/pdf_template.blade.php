<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan & Laba - Toserba Hasan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1e293b; font-size: 18px; }
        .header p { margin: 4px 0 0 0; color: #64748b; font-size: 12px; }
        .info { margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #2563eb; color: white; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { border-bottom: 1px solid #e2e8f0; padding: 7px 8px; font-size: 10px; }
        .text-right { text-align: right; }
        .summary-card { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .summary-card td { border: none; padding: 4px 8px; }
        .footer { margin-top: 25px; text-align: right; font-style: italic; font-size: 10px; color: #64748b; }
        .profit { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TOSERBA HASAN</h2>
        <p>Laporan Penjualan & Laba Kotor</p>
    </div>

    <div class="info">
        <strong>Periode:</strong> {{ $start ?? 'Semua' }} s/d {{ $end ?? 'Sekarang' }}<br>
        <strong>Metode:</strong> {{ ['tunai' => 'Tunai', 'qris' => 'QRIS'][$metode ?? ''] ?? 'Semua' }}<br>
        <strong>Waktu Cetak:</strong> {{ now()->format('d/m/Y H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th>Tanggal</th>
                <th class="text-right">Omzet</th>
                <th class="text-right">Modal (HPP)</th>
                <th class="text-right">Laba Kotor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $t)
            <tr>
                <td>#{{ $t->transaction_id }}</td>
                <td>{{ $t->cashier_name ?? 'Kasir' }}</td>
                <td>{{ $t->metode_label }}</td>
                <td>{{ $t->transaction_date ? $t->transaction_date->format('d/m/Y H:i') : '-' }}</td>
                <td class="text-right">Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($t->total_modal, 0, ',', '.') }}</td>
                <td class="text-right profit">Rp {{ number_format($t->laba_kotor, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 15px; color: #94a3b8;">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-card" style="margin-left: auto; width: 45%; margin-top: 20px; border: 1px solid #cbd5e1; background-color: #f8fafc;">
        <tr>
            <td style="font-weight: bold;">Total Omzet Penjualan</td>
            <td class="text-right" style="font-weight: bold;">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="color: #64748b;">Total Modal Barang (HPP)</td>
            <td class="text-right" style="color: #64748b;">Rp {{ number_format($totalModal ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #94a3b8;">
            <td style="font-weight: bold; color: #16a34a; font-size: 12px;">Total Laba Kotor</td>
            <td class="text-right" style="font-weight: bold; color: #16a34a; font-size: 12px;">Rp {{ number_format($totalLabaKotor ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem POS Toserba Hasan.</p>
    </div>
</body>
</html>
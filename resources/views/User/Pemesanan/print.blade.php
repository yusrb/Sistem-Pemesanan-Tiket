<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pemesanan #{{ $pemesanan->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .header h1 { color: #007bff; margin: 0; font-size: 24px; }
        .info { margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .label { font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .total { text-align: right; font-weight: bold; font-size: 14px; margin-top: 20px; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .status.paid { background-color: #d4edda; color: #155724; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>STRUK PEMESANAN TIKET KERETA</h1>
            <p>ID Pemesanan: #{{ $pemesanan->id }}</p>
        </div>

        <div class="info">
            <div class="info-row">
                <span class="label">Nama Pemesan:</span>
                <span>{{ $pemesanan->user->nama }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span>{{ $pemesanan->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="label">No. Telepon:</span>
                <span>{{ $pemesanan->user->no_telepon ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Kereta:</span>
                <span>{{ $pemesanan->jadwal->kereta->nama_kereta }}</span>
            </div>
            <div class="info-row">
                <span class="label">Rute:</span>
                <span>{{ $pemesanan->jadwal->stasiun_awal }} - {{ $pemesanan->jadwal->stasiun_akhir }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal Keberangkatan:</span>
                <span>{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jam Berangkat:</span>
                <span>{{ $pemesanan->jadwal->jam_berangkat }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jam Sampai:</span>
                <span>{{ $pemesanan->jadwal->jam_sampai }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jumlah Penumpang:</span>
                <span>{{ $pemesanan->jumlah_penumpang }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="status paid">SUDAH DIBAYAR</span>
            </div>
        </div>

        <h3 style="margin: 20px 0 10px 0; font-size: 16px;">Detail Penumpang & Tiket</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Penumpang</th>
                    <th>Gerbong</th>
                    <th>Kode Tiket</th>
                    <th>Harga Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pemesanan->detailPemesanans as $detail)
                    <tr>
                        <td>{{ $detail->penumpang->nama }}</td>
                        <td>{{ $detail->gerbong->kode_gerbong }}</td>
                        <td>{{ $detail->kode }}</td>
                        <td>Rp {{ number_format($pemesanan->jadwal->harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total Harga: Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
        </div>

        <div class="footer">
            <p>Terima kasih telah memilih layanan kami!</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }} WIB</p>
            <p>Hubungi customer service untuk pertanyaan lebih lanjut.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 10pt; }
        .container { width: 100%; max-width: 300px; margin: 0 auto; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        .bold { font-weight: bold; }
        table { width: 100%; }
        td { vertical-align: top; }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <h3 style="margin-bottom: 5px;">SMOLIE GIFT</h3>
            Perumahan Sukorejo Indah No. 123<br>
            WA: 0878-4760-4348
        </div>
        
        <div class="line"></div>
        
        <table>
            <tr>
                <td>No. TRX</td>
                <td class="text-right">{{ $transaksi->kode_transaksi }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ date('d-m-Y H:i', strtotime($transaksi->created_at)) }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $transaksi->nama_pembeli }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td class="text-right">{{ strtoupper($transaksi->metode_pembayaran) }}</td>
            </tr>
        </table>

        <div class="line"></div>

        <table>
            @foreach($details as $item)
            <tr>
                <td colspan="2">{{ $item->nama_produk }}</td>
            </tr>
            <tr>
                <td>{{ $item->jumlah }} x {{ number_format($item->subtotal / $item->jumlah, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>

        <div class="line"></div>

        <table>
            <tr class="bold">
                <td>TOTAL</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>
        
        <div class="text-center" style="margin-top: 10px;">
            <small>Terima Kasih atas Kunjungan Anda!</small><br>
            <small>Selamat Menikmati</small>
        </div>
    </div>
</body>
</html>
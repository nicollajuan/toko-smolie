<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - Smolie Gift</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 10pt; color: #000; }
        .container { width: 100%; max-width: 300px; margin: 0 auto; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 7px 0; }
        .bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
    </style>
</head>
<body>
    <div class="container">
        {{-- HEADER STRUK --}}
        <div class="text-center">
            <h3 style="margin: 0 0 5px 0; font-size: 14pt;">SMOLIE GIFT</h3>
            Surabaya, Jawa Timur<br>
            WA: 0895-3958-10940
        </div>
        
        <div class="line"></div>
        
        {{-- INFO TRANSAKSI --}}
        <table>
            <tr>
                <td>No. TRX</td>
                <td class="text-right">{{ $transaksi->kode_transaksi }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ \Carbon\Carbon::parse($transaksi->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pelanggan</td>
                <td class="text-right">{{ $transaksi->nama_pembeli }}</td>
            </tr>
        </table>

        <div class="line"></div>

        {{-- DAFTAR BELANJAAN --}}
        <table>
            @php $totalItem = 0; @endphp
            @foreach($details as $item)
                @php $totalItem += $item->jumlah; @endphp
                <tr>
                    <td colspan="2" class="bold">{{ $item->nama_produk }}</td>
                </tr>
                <tr>
                    <td>{{ $item->jumlah }} x {{ number_format($item->subtotal / $item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>

        {{-- PERHITUNGAN TOTAL --}}
        <table>
            <tr>
                <td>Total Item</td>
                <td class="text-right">{{ $totalItem }} Item</td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr class="bold" style="font-size: 11pt;">
                <td style="padding-top: 5px;">TOTAL</td>
                <td class="text-right" style="padding-top: 5px;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="line"></div>

        {{-- METODE & KEMBALIAN --}}
        <table>
            <tr>
                <td>Metode</td>
                <td class="text-right">{{ strtoupper($transaksi->metode_pembayaran) }}</td>
            </tr>

            @if(strtolower($transaksi->metode_pembayaran) == 'tunai')
                <tr>
                    <td>Diterima</td>
                    <td class="text-right">Rp {{ number_format($transaksi->uang_diterima ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembalian</td>
                    <td class="text-right">Rp {{ number_format($transaksi->kembalian ?? 0, 0, ',', '.') }}</td>
                </tr>
            @else
                <tr>
                    <td>Status</td>
                    <td class="text-right">LUNAS (QRIS)</td>
                </tr>
            @endif
        </table>

        <div class="line"></div>
        
        {{-- FOOTER STRUK --}}
        <div class="text-center" style="margin-top: 15px;">
            <div class="bold">Terima Kasih atas Kunjungan Anda!</div>
            <div>Selamat Menikmati</div>
            <div style="margin-top: 10px; font-size: 8pt; color: #555;">
                * Struk ini adalah bukti pembayaran yang sah *
            </div>
        </div>
    </div>
</body>
</html>
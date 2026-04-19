<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #DD3827; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #DD3827; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f5f5f5; }
        .total { text-align: right; font-weight: bold; font-size: 18px; color: #DD3827; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMOLIE GIFT</h1>
        <p>Invoice Pembelian Kado & Suvenir Custom</p>
    </div>

    <table style="border: none;">
        <tr>
            <td style="border: none;">
                <strong>Detail Pemesan:</strong><br>
                Nama: {{ $transaction->customer_name }}<br>
                WhatsApp: {{ $transaction->customer_wa }}
            </td>
            <td style="border: none; text-align: right;">
                <strong>Order ID:</strong> #INV-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}<br>
                <strong>Tanggal:</strong> {{ $transaction->created_at->format('d M Y') }}<br>
                <strong>Status:</strong> {{ $transaction->status }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Deskripsi Produk</th>
                <th>Metode Pembayaran</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Pesanan Kustom Smolie Gift<br>
                    <small style="color: gray;">Catatan/Event: {{ $transaction->event_info ?? '-' }}</small>
                </td>
                <td>{{ $transaction->payment_method }}</td>
                <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <p class="total">Grand Total: Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>

    <div style="margin-top: 50px; text-align: center; color: gray; font-size: 12px;">
        <p>Terima kasih telah berbelanja di Smolie Gift!</p>
        <p>Simpan invoice ini sebagai bukti pemesanan yang sah.</p>
    </div>

</body>
</html>
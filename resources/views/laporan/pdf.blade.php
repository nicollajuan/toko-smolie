<!DOCTYPE html>
<html>
<head>
    <title>Laporan Riwayat Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; color: #555; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #444; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; padding: 8px; }
        td { padding: 6px 8px; vertical-align: top; }

        /* CSS untuk Badge (Label Warna) agar mirip Bootstrap */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            border-radius: 4px;
            text-transform: capitalize;
            margin-bottom: 2px;
        }
        .bg-danger { background-color: #dc3545; } /* Merah (Delivery) */
        .bg-info { background-color: #0dcaf0; color: #000; } /* Biru Muda (Dine In) */
        .bg-warning { background-color: #ffc107; color: #000; } /* Kuning (Takeaway) */
        .bg-primary { background-color: #0d6efd; } /* Biru (Tunai) */
        .bg-dark { background-color: #212529; } /* Hitam (QRIS) */
        .bg-success { background-color: #198754; } /* Hijau (Selesai) */
        .bg-secondary { background-color: #6c757d; } /* Abu (Pending) */

        /* Utility Text */
        .text-bold { font-weight: bold; }
        .text-small { font-size: 9px; color: #555; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Box Alamat */
        .box-alamat {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 9px;
            margin-top: 3px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Smolie Gift</p>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="12%">Kode TRX</th>
                <th width="15%">Pembeli</th>
                <th width="20%">Layanan</th> {{-- KOLOM BARU --}}
                <th width="10%">Metode</th> {{-- KOLOM BARU --}}
                <th width="13%">Total</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $data)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ date('d/m/Y H:i', strtotime($data->created_at)) }}</td>
                <td class="text-bold text-center">{{ $data->kode_transaksi }}</td>
                
                <td>
                    <span class="text-bold">{{ $data->nama_pembeli }}</span><br>
                    <span class="text-small">{{ $data->no_hp ?? '-' }}</span>
                </td>

                {{-- KOLOM LAYANAN (DENGAN ALAMAT) --}}
                <td>
                    @if($data->jenis_pesanan == 'delivery')
                        <span class="badge bg-danger">Delivery</span>
                        <div class="box-alamat">
                            <strong>Alamat:</strong> {{ $data->alamat_pengiriman }}<br>
                            @if($data->detail_rumah)
                                <i>Note: {{ $data->detail_rumah }}</i>
                            @endif
                        </div>
                    @elseif($data->jenis_pesanan == 'dine_in')
                        <span class="badge bg-info">Dine In</span>
                    @else
                        <span class="badge bg-warning">Takeaway</span>
                    @endif
                </td>

                {{-- KOLOM METODE BAYAR --}}
                <td class="text-center">
                    @if($data->metode_pembayaran == 'qris')
                        <span class="badge bg-dark">QRIS</span>
                    @else
                        <span class="badge bg-primary">Tunai</span>
                    @endif
                </td>

                <td class="text-right text-bold">Rp {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                
                <td class="text-center">
                    @if($data->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-secondary">Pending</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right text-bold" style="padding: 10px;">Total Pendapatan:</td>
                <td colspan="2" class="text-bold" style="padding: 10px; font-size: 13px;">
                    Rp {{ number_format($laporan->sum('total_harga'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
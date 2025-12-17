<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Transaksi::all();
    }

    // Judul Kolom di Excel
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kode Transaksi',
            'Nama Pembeli',
            'No HP',
            'Total Bayar',
            'Status',
        ];
    }

    // Data yang dimasukkan
    public function map($transaksi): array
    {
        return [
            $transaksi->id,
            $transaksi->created_at->format('d-m-Y H:i'),
            $transaksi->kode_transaksi,
            $transaksi->nama_pembeli,
            $transaksi->no_hp ?? '-',
            'Rp ' . number_format($transaksi->total_harga, 0, ',', '.'),
            $transaksi->status,
        ];
    }
}
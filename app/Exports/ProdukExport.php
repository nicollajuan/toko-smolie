<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Produk;

class ProdukExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Produk::all();
    }

    public function headings() : array{
        return[
            'No',
            'Nama Produk',
            'Kategori',
            'Harga',
            'Stok',
            'Created At',
            'Updated At',
        ];
    }
}

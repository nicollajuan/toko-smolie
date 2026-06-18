<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Pembeli POST pesanan baru
    public function store(Request $request)
    {
        $transaksi = Transaksi::create([
            'nama_pembeli'      => $request->nama_pembeli,
            'no_hp'             => $request->no_hp,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jenis_pesanan'     => $request->jenis_pesanan,
            'kode_transaksi'    => $request->kode_transaksi,
            'total_harga'       => $request->total_harga,
            'items_json'        => $request->items_json ?? '',
            'status'            => 'pending',
            'uang_diterima'     => 0,
            'kembalian'         => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    // Admin GET transaksi yang masih pending
    public function index()
    {
        $data = Transaksi::where('status', 'pending')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Admin GET semua transaksi
    public function all()
    {
        $data = Transaksi::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // Admin konfirmasi
    public function confirm($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = 'selesai';
        $transaksi->save();

        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    // Admin batalkan
    public function cancel($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = 'dibatalkan';
        $transaksi->save();

        return response()->json([
            'status' => 'success',
            'data' => $transaksi
        ]);
    }

    // Riwayat pembeli
    public function history($email)
    {
        $data = Transaksi::where('nama_pembeli', $email)
                         ->where('status', 'selesai')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
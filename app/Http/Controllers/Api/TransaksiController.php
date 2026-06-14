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
        try {
            $transaksi = Transaksi::create([
                'nama_pembeli'      => $request->nama_pembeli,
                'no_hp'             => $request->no_hp ?? '-',
                'metode_pembayaran' => $request->metode_pembayaran,
                'jenis_pesanan'     => $request->jenis_pesanan ?? 'Online',
                'kode_transaksi'    => $request->kode_transaksi,
                'total_harga'       => $request->total_harga,
                'status'            => 'pending',
            ]);
            return response()->json(['status' => 'success', 'data' => $transaksi]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Admin GET semua transaksi pending
    public function index()
    {
        $data = Transaksi::where('status', 'pending')
                         ->orderBy('created_at', 'desc')
                         ->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    // Admin konfirmasi → ubah status jadi selesai
    public function confirm($id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->status = 'selesai';
            $transaksi->save();
            return response()->json(['status' => 'success', 'data' => $transaksi]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // Pembeli GET riwayat berdasarkan nama_pembeli (email)
    public function history($email)
    {
        $data = Transaksi::where('nama_pembeli', $email)
                         ->where('status', 'selesai')
                         ->orderBy('created_at', 'desc')
                         ->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    // GET semua transaksi (pending + selesai) untuk admin
    public function all()
    {
        $data = Transaksi::orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
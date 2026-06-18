<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('transaksi.details.produk')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($review) {
                $transaksi = $review->transaksi;

                // Coba dari relasi details dulu
                $produkList = collect();
                if ($transaksi?->details && $transaksi->details->count() > 0) {
                    $produkList = $transaksi->details->map(fn($d) => [
                        'nama_produk' => $d->produk?->nama_produk ?? $d->nama_produk ?? '-',
                        'qty'         => $d->qty ?? 1,
                        'harga'       => $d->harga ?? 0,
                    ]);
                }
                // Fallback ke items_json jika details kosong
                elseif (!empty($transaksi?->items_json)) {
                    try {
                        $items = json_decode($transaksi->items_json, true);
                        $produkList = collect($items)->map(fn($item) => [
                            'nama_produk' => $item['nama'] ?? $item['name'] ?? '-',
                            'qty'         => $item['jumlah'] ?? $item['qty'] ?? 1,
                            'harga'       => $item['harga'] ?? 0,
                        ]);
                    } catch (\Exception $e) {
                        $produkList = collect();
                    }
                }

                return [
                    'id'             => $review->id,
                    'transaksi_id'   => $review->transaksi_id,
                    'kode_transaksi' => $transaksi?->kode_transaksi ?? '-',
                    'nama_pembeli'   => $transaksi?->nama_pembeli ?? '-',
                    'no_hp'          => $transaksi?->no_hp ?? '-',
                    'total_harga'    => $transaksi?->total_harga ?? 0,
                    'jenis_pesanan'  => $transaksi?->jenis_pesanan ?? '-',
                    'produk'         => $produkList,
                    'rating'         => $review->rating,
                    'komentar'       => $review->komentar,
                    'foto'           => $review->foto,
                    'video'          => $review->video,
                    'created_at'     => $review->created_at,
                ];
            });

        return response()->json(['status' => 'success', 'data' => $reviews]);
    }

    public function store(Request $request)
    {
        Log::info('Review masuk:', $request->all());

        $fotoName  = null;
        $videoName = null;

        if (!empty($request->foto)) {
            try {
                $fotoData = base64_decode($request->foto);
                $fotoName = 'review_foto_' . time() . '.jpg';
                file_put_contents(public_path('reviews/' . $fotoName), $fotoData);
            } catch (\Exception $e) { $fotoName = null; }
        }

        if (!empty($request->video)) {
            try {
                $videoData = base64_decode($request->video);
                $videoName = 'review_video_' . time() . '.mp4';
                file_put_contents(public_path('reviews/' . $videoName), $videoData);
            } catch (\Exception $e) { $videoName = null; }
        }

        $review = Review::create([
            'transaksi_id' => $request->transaksi_id ?? 0,
            'user_id'      => $request->user_id ?? 0,
            'rating'       => $request->rating ?? 5,
            'komentar'     => $request->ulasan ?? $request->komentar ?? '',
            'foto'         => $fotoName,
            'video'        => $videoName,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Ulasan berhasil dikirim',
            'data'    => $review
        ]);
    }
}
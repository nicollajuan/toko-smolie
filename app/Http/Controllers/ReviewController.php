<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
{
    // Ambil data review beserta data User dan Transaksinya (Eager Loading)
    $reviews = Review::with(['user', 'transaksi'])->latest()->get();

    return view('admin.reviews.index', compact('reviews'));
}

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        // Cek apakah user sudah pernah review transaksi ini (mencegah double review)
        $cek = Review::where('transaksi_id', $request->transaksi_id)->first();
        if($cek) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Review::create([
            'transaksi_id' => $request->transaksi_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
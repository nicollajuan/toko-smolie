<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function kirimPesanPembeli(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $request->validate(['pesan' => 'required|string', 'user_id' => 'nullable|integer']);

        // Logika cerdas: Deteksi apakah dari Dashboard Admin atau Halaman Depan
        if ($request->has('user_id') && in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            $userId = $request->user_id; 
            $pengirim = 'admin';
        } else {
            $userId = Auth::id(); 
            $pengirim = 'pembeli';
        }

        try {
            $message = Message::create([
                'user_id' => $userId,
                'pesan' => $request->pesan,
                'pengirim' => $pengirim,
                'is_read' => false
            ]);

            return response()->json([
                'status' => 'success', 
                'pesan' => $message->pesan,
                'waktu' => $message->created_at->format('H:i')
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function adminChat()
    {
        $userIds = Message::select('user_id')->groupBy('user_id')->latest()->pluck('user_id');
        $kontak = User::whereIn('id', $userIds)->get();
        return view('admin.reviews.chat', compact('kontak'));
    }

    public function getMessages($user_id)
    {
        $messages = Message::where('user_id', $user_id)->orderBy('created_at', 'asc')->get();
        if (in_array(Auth::user()->usertype, ['admin', 'kasir'])) {
            Message::where('user_id', $user_id)->where('pengirim', 'pembeli')->update(['is_read' => true]);
        }
        return response()->json($messages);
    }
}
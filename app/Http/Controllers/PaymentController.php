<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman pembayaran dengan QRIS
     */
    public function show($id)
    {
        // Ambil transaksi
        $transaksi = Transaksi::with('user')->findOrFail($id);

        // Validasi: hanya user pemilik transaksi atau admin yang bisa akses
        if (Auth::user()->id !== $transaksi->user_id && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Ambil data rekening admin
        $admin = \App\Models\User::where('usertype', 'admin')->first();

        // Generate QRIS data
        $qrisUrl = $this->generateQrisUrl($transaksi, $admin);
        $qrCodeImage = $this->generateQrCode($qrisUrl);

        // Simpan QRIS data ke transaksi
        if ($transaksi->metode_pembayaran === 'qris' && !$transaksi->qris_data) {
            $transaksi->update([
                'qris_data' => $qrisUrl,
                'status_pembayaran' => $transaksi->status_pembayaran ?? 'pending',
            ]);
        }

        return view('pembeli.payment', [
            'transaksi' => $transaksi,
            'qrCode' => $qrCodeImage,
            'qrisUrl' => $qrisUrl,
            'admin' => $admin,
        ]);
    }

    /**
     * Generate QRIS URL berdasarkan nominal dan data rekening admin
     * Format: https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<data>
     * Atau menggunakan static QRIS untuk demo
     */
    private function generateQrisUrl(Transaksi $transaksi, $admin = null)
    {
        // QRIS Payload - Format berdasarkan spesifikasi EMV Co
        // Untuk implementasi real, gunakan library seperti php-qris
        
        $nominal = intval($transaksi->total_harga);
        $merchantName = $admin && $admin->nama_pemilik_rekening 
            ? strtoupper($admin->nama_pemilik_rekening) 
            : 'Smolie Gift';
        $merchantCity = 'Jakarta';
        $nomorRekening = $admin && $admin->nomor_rekening 
            ? $admin->nomor_rekening 
            : 'UNKNOWN';
        
        // Format QRIS dengan informasi admin
        // Struktur: DATA ADMIN | NOMOR REKENING | NOMINAL | KODE TRANSAKSI
        $qrisData = json_encode([
            'merchant' => $merchantName,
            'bank' => $admin && $admin->nama_bank ? $admin->nama_bank : 'Unknown',
            'rekening' => $nomorRekening,
            'nominal' => $nominal,
            'transaksi_id' => $transaksi->id,
            'kode_transaksi' => $transaksi->kode_transaksi,
            'timestamp' => now()->toIso8601String(),
        ]);
        
        // Return QRIS string yang berisi informasi admin
        return $qrisData;
    }

    /**
     * Generate QR Code dari QRIS data
     */
    private function generateQrCode($qrisUrl)
    {
        try {
            $qrCode = new QrCode($qrisUrl);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // Convert ke base64 untuk inline display
            return base64_encode($result->getString());
        } catch (\Exception $e) {
            // Jika gagal, return error image
            return null;
        }
    }

    /**
     * Proses upload bukti pembayaran
     */
    public function uploadProof(Request $request, $id)
    {
        // Validasi
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload',
            'bukti_pembayaran.mimes' => 'Format file harus JPG, PNG, atau PDF',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        // Ambil transaksi
        $transaksi = Transaksi::findOrFail($id);

        // Validasi: hanya user pemilik transaksi yang bisa upload
        if (Auth::user()->id !== $transaksi->user_id) {
            abort(403, 'Unauthorized');
        }

        try {
            // Hapus file lama jika ada
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists('bukti_pembayaran/' . $transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete('bukti_pembayaran/' . $transaksi->bukti_pembayaran);
            }

            // Upload file baru
            $file = $request->file('bukti_pembayaran');
            $filename = 'TRX-' . $transaksi->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            $path = Storage::disk('public')->putFileAs(
                'bukti_pembayaran',
                $file,
                $filename
            );

            // Update transaksi
            $transaksi->update([
                'bukti_pembayaran' => $filename,
                'status_pembayaran' => 'pending', // Status pending (menunggu verifikasi admin)
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Admin verifikasi pembayaran
     */
    public function verifyPayment(Request $request, $id)
    {
        // Hanya admin yang bisa verifikasi
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status_pembayaran' => 'required|in:berhasil,gagal',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        
        $transaksi->update([
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        $statusText = $request->status_pembayaran === 'berhasil' ? 'Berhasil' : 'Gagal';
        
        return redirect()->back()->with('success', 'Status pembayaran diperbarui menjadi: ' . $statusText);
    }

    /**
     * Download bukti pembayaran
     */
    public function downloadProof($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Validasi: hanya admin atau pemilik yang bisa download
        if (Auth::user()->id !== $transaksi->user_id && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if (!$transaksi->bukti_pembayaran) {
            return redirect()->back()->with('error', 'Bukti pembayaran tidak tersedia');
        }

        $path = storage_path('app/public/bukti_pembayaran/' . $transaksi->bukti_pembayaran);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($path);
    }
}

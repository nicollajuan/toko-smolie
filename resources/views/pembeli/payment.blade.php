<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Smolie Gift</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #e4002b;
            --text-dark: #202124;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffff 0%, #ffff 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .payment-container {
            max-width: 900px; /* Dipersempit agar desain terpusat & rapi */
            width: 95%;
            margin: 0 auto;
        }

        .card-payment {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header-payment {
            background: linear-gradient(135deg, var(--primary-color) 0%, #b8001f 100%);
            color: white;
            padding: 30px;
            border-radius: 20px 20px 0 0;
        }

        .qris-section {
            text-align: center;
            padding: 30px;
            background: #f9f9f9;
            border-radius: 15px;
        }

        .qr-code-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .transaction-details {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .total-amount {
            font-size: 28px;
            color: var(--primary-color);
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-success { background: #d4edda; color: #155724; }
        .status-failed  { background: #f8d7da; color: #721c24; }

        .upload-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            border: 2px dashed #ddd;
        }

        .btn-upload {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-upload:hover {
            background: #b8001f;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(228, 0, 43, 0.3);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
        }

        .file-input-wrapper input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .instruction-box {
            background: #e8f4f8;
            border-left: 4px solid #0dcaf0;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
        }

        .instruction-box h6 { color: #0c5460; margin-bottom: 10px; font-weight: 600; }
        .instruction-box ul { margin-bottom: 0; padding-left: 20px; color: #0c5460; }
        .instruction-box li { margin-bottom: 5px; font-size: 0.9rem; }

        .proof-preview {
            margin-top: 15px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        @media (max-width: 576px) {
            .card-header-payment { padding: 15px; }
            .card-header-payment h3 { font-size: 1rem; }
            .total-amount { font-size: 1.4rem; }
            .transaction-details, .upload-section, .qris-section { padding: 15px; }
            .detail-row { flex-direction: column; gap: 3px; }
        }
    </style>
</head>
<body>

<div class="payment-container">
    {{-- HEADER --}}
    <div class="text-white mb-4">
        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
            <a href="{{ route('transaksi.index') }}" class="text-white text-decoration-none fw-bold">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Transaksi
            </a>
        @else
            <a href="/" class="text-white text-decoration-none fw-bold">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu Utama
            </a>
        @endif
        <h1 class="fw-bold mt-3">Pembayaran Pesanan</h1>
    </div>

    <div class="card card-payment">
        {{-- CARD HEADER --}}
        <div class="card-header-payment">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-2">Kode Pesanan: {{ $transaksi->kode_transaksi }}</h3>
                    <p class="mb-0 opacity-75">Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <span class="status-badge status-{{ strtolower($transaksi->status_pembayaran ?? 'pending') }}">
                        @if($transaksi->status_pembayaran === 'berhasil')
                            <i class="bi bi-check-circle me-1"></i> Berhasil
                        @elseif($transaksi->status_pembayaran === 'gagal')
                            <i class="bi bi-x-circle me-1"></i> Gagal
                        @else
                            <i class="bi bi-clock me-1"></i> Menunggu Pembayaran
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4 mb-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4 mb-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger m-4 mb-0">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- MAIN CONTENT (Layout Tengah/Stacked) --}}
            <div class="p-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        
                        {{-- 1. RINCIAN PESANAN --}}
                        <div class="transaction-details">
                            <h5 class="fw-bold text-dark mb-4">Rincian Pesanan</h5>
                            
                            <div class="detail-row">
                                <span class="detail-label">Nama Pemesan</span>
                                <span class="detail-value">{{ $transaksi->nama_pembeli }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Nomor Telepon</span>
                                <span class="detail-value">{{ $transaksi->user->no_hp ?? '-' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Jenis Pesanan</span>
                                <span class="detail-value">
                                    @if($transaksi->jenis_pesanan === 'dine_in')
                                        <i class="bi bi-shop me-1"></i> Ambil di Toko
                                    @elseif($transaksi->jenis_pesanan === 'takeaway')
                                        <i class="bi bi-bag me-1"></i> Takeaway
                                    @else
                                        <i class="bi bi-truck me-1"></i> Delivery
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Metode Pembayaran</span>
                                <span class="detail-value">
                                    @if($transaksi->metode_pembayaran === 'qris')
                                        <i class="bi bi-qr-code me-1"></i> QRIS
                                    @else
                                        <i class="bi bi-coin me-1"></i> Tunai
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row pb-3" style="border-bottom: 2px solid #ddd !important;">
                                <span class="detail-label">Subtotal</span>
                                <span class="detail-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="detail-row pt-3" style="border: none;">
                                <span class="detail-label fw-bold fs-6">Total Bayar</span>
                                <span class="total-amount">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- 2. METODE QRIS (ATAS: QR, BAWAH: UPLOAD) --}}
                        @if($transaksi->metode_pembayaran === 'qris')
                            <div class="qris-section shadow-sm mb-4">
                                <h4 class="fw-bold text-dark mb-3">Scan QRIS untuk Pembayaran</h4>

                                {{-- NOMINAL PANDUAN --}}
                                <div class="mb-4 py-3 px-4 rounded-3 shadow-sm mx-auto"
                                     style="background: linear-gradient(135deg, #e4002b, #b8001f); color:white; max-width: 400px;">
                                    <div class="small mb-1" style="opacity:0.8;">Nominal yang harus dibayar:</div>
                                    <div style="font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;">
                                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </div>
                                    <div class="small mt-1" style="opacity:0.75;">
                                        <i class="bi bi-exclamation-circle me-1"></i> Masukkan nominal ini di aplikasi
                                    </div>
                                </div>

                                {{-- GAMBAR QRIS (ANTI PENYEK) --}}
                                @if(!empty($gambarQris))
                                    <div class="qr-code-box mb-3">
                                        <img src="{{ $gambarQris }}" alt="QRIS Smolie Gift" class="img-fluid object-fit-contain border rounded p-2" style="max-width: 250px; height: auto;">
                                    </div>
                                    <p class="text-muted small mb-4">
                                        <i class="bi bi-shield-check text-success me-1"></i> QRIS resmi Smolie Gift
                                    </p>
                                @elseif($qrCode)
                                    <div class="alert alert-warning py-2 px-3 mb-3 text-start small">
                                        <i class="bi bi-info-circle me-1"></i> Gambar QRIS toko belum diupload admin. QR di bawah hanya referensi.
                                    </div>
                                    <div class="qr-code-box mb-4">
                                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code QRIS" class="img-fluid object-fit-contain border rounded p-2" style="max-width: 250px; height: auto;">
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-4">
                                        <i class="bi bi-exclamation-triangle me-2"></i> QRIS belum tersedia. Silakan hubungi admin.
                                    </div>
                                @endif

                                {{-- CARA PEMBAYARAN & REKENING --}}
                                <div class="row text-start mt-4">
                                    <div class="col-md-6">
                                        <div class="instruction-box h-100">
                                            <h6><i class="bi bi-info-circle-fill me-2"></i>Cara Pembayaran</h6>
                                            <ul>
                                                <li>Buka aplikasi e-wallet / m-banking</li>
                                                <li>Pilih menu <strong>Scan QR</strong></li>
                                                <li>Arahkan kamera ke gambar QRIS</li>
                                                <li>Masukkan nominal: <strong style="color:#e4002b;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></li>
                                                <li>Konfirmasi & <strong>screenshot bukti</strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        @if($admin && ($admin->nama_bank || $admin->nomor_rekening))
                                        <div class="instruction-box h-100" style="background: #e7f3ff; border-left-color: #0066cc;">
                                            <h6 style="color: #003d99;"><i class="bi bi-bank me-2"></i>Data Penerima</h6>
                                            <div style="color: #003d99; font-size: 0.95rem;">
                                                @if($admin->nama_bank) <p class="mb-2"><strong>Bank:</strong> {{ $admin->nama_bank }}</p> @endif
                                                @if($admin->nomor_rekening) <p class="mb-2"><strong>No. Rek:</strong> {{ $admin->nomor_rekening }}</p> @endif
                                                @if($admin->nama_pemilik_rekening) <p class="mb-0"><strong>A/n:</strong> {{ strtoupper($admin->nama_pemilik_rekening) }}</p> @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <hr class="border-secondary my-4">

                                {{-- FORM UPLOAD BUKTI (DI BAWAH QR) --}}
                                <h5 class="fw-bold text-dark mb-3">Upload Bukti Pembayaran</h5>
                                
                                @if($transaksi->bukti_pembayaran)
                                    <div class="proof-preview text-center mx-auto" style="max-width: 500px;">
                                        <div class="d-flex align-items-center justify-content-center mb-3">
                                            <i class="bi bi-check-circle text-success me-2" style="font-size: 20px;"></i>
                                            <span class="fw-bold text-success">Bukti Sudah Diupload</span>
                                        </div>
                                        <p class="mb-2 text-muted">File: <strong>{{ $transaksi->bukti_pembayaran }}</strong></p>
                                        <p class="mb-3 text-muted">Status:
                                            @if($transaksi->status_pembayaran === 'berhasil')
                                                <span class="badge bg-success">Terverifikasi</span>
                                            @elseif($transaksi->status_pembayaran === 'gagal')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                            @endif
                                        </p>
                                        <a href="{{ route('pembayaran.downloadProof', $transaksi->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i> Download File
                                        </a>
                                    </div>
                                @else
                                    <div class="upload-section mx-auto" style="max-width: 500px;">
                                        <i class="bi bi-cloud-upload fs-1 text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-3">Upload screenshot/bukti transfer Anda di sini.</p>

                                        <form action="{{ route('pembayaran.uploadProof', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="file-input-wrapper mb-3">
                                                <input type="file" id="buktiFile" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required>
                                                <label for="buktiFile" class="btn btn-upload w-100">
                                                    <i class="bi bi-plus-circle me-2"></i>Pilih File
                                                </label>
                                            </div>
                                            <div id="fileInfo" class="text-center mb-3" style="display: none;">
                                                <small id="fileName" class="text-muted"></small>
                                            </div>
                                            <div class="form-text mb-3">Format: JPG, PNG, PDF. Max: 2MB.</div>
                                            
                                            <button type="submit" class="btn btn-upload w-100">
                                                <i class="bi bi-upload me-2"></i>Kirim Bukti Pembayaran
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                        {{-- 3. JIKA METODE TUNAI --}}
                        @elseif($transaksi->metode_pembayaran === 'tunai')
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-body text-center p-5">
                                    <i class="bi bi-cash-coin text-success mb-3" style="font-size: 80px;"></i>
                                    <h4 class="fw-bold text-dark">Pembayaran Tunai / COD</h4>
                                    <p class="text-muted mt-2">Tidak perlu upload bukti pembayaran.<br>Silakan bayar langsung kepada kasir saat pesanan Anda terima.</p>
                                    <div class="alert alert-success mt-4 border-0 shadow-sm mx-auto" style="background-color: #d1e7dd; color: #0f5132; border-radius: 12px; max-width: 500px;">
                                        <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                                        <span class="align-middle">Pesanan Anda sudah tercatat. Silakan tunggu konfirmasi.</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- FOOTER BUTTONS --}}
            <div class="p-4 border-top d-flex gap-2 justify-content-between align-items-center flex-wrap">
                @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary fw-bold">
                        <i class="bi bi-arrow-left me-2"></i>Daftar Transaksi
                    </a>
                @else
                    <a href="{{ route('pembeli.riwayat') }}" class="btn btn-outline-secondary fw-bold">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
                    </a>
                @endif

                @if($transaksi->metode_pembayaran === 'qris' && !$transaksi->bukti_pembayaran && $transaksi->status_pembayaran === 'pending')
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Setelah bayar, jangan lupa upload bukti
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('buktiFile')?.addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        const fileInfo = document.getElementById('fileInfo');
        const fileNameSpan = document.getElementById('fileName');
        if (fileName) {
            fileNameSpan.textContent = `File dipilih: ${fileName}`;
            fileInfo.style.display = 'block';
        }
    });
</script>

</body>
</html>
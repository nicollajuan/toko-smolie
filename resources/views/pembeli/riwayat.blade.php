<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="{{ asset('template/img/smolie_icon.png') }}" type="image/jpeg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Smolie Gift</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Google Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #e4002b; 
            --bg-light: #f8f9fa;
            --text-dark: #202124;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
        }

        /* NAVBAR STYLE */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--text-dark) !important;
            font-size: 1.1rem;
        }

        /* HISTORY CARD */
        .history-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .history-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        
        .card-header-custom {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .trx-code {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1rem;
        }
        
        .trx-date {
            font-size: 0.8rem;
            color: #888;
        }

        .card-body-custom {
            padding: 20px;
        }

        .price-total {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* BADGES */
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-method {
            display: inline-flex;
            align-items: center;
            background-color: #f1f2f6;
            color: #555;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* BUTTONS */
        .btn-back {
            background-color: #f1f2f6;
            color: var(--text-dark);
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }
        .btn-back:hover {
            background-color: #e2e6ea;
            color: var(--text-dark);
        }

        .btn-struk {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
            border-radius: 30px;
            padding: 6px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-struk:hover {
            background: var(--primary-color);
            color: white;
        }
        
        /* TOMBOL REVIEW */
        .btn-review {
            background-color: #ffc107;
            color: #212529;
            border: none;
            border-radius: 30px;
            padding: 6px 15px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 5px;
        }
        .btn-review:hover {
            background-color: #e0a800;
        }

        /* EMPTY STATE */
        .empty-state-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 15px;
        }

        /* --- CSS RATING BINTANG (YANG SUDAH DIPERBAIKI) --- */
        .rating-css div {
            color: #ffe400;
            font-size: 30px;
            font-family: sans-serif;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            padding: 10px 0;
        }
        .rating-css input {
            display: none;
        }
        .rating-css input + label {
            font-size: 40px;
            text-shadow: 1px 1px 0 #8f8420;
            cursor: pointer;
            color: #ccc; /* Warna default abu-abu */
            transition: all 0.2s ease;
        }
        
        /* LOGIKA FIX: Bintang yang diklik DAN semua bintang sebelumnya (visual kiri) jadi kuning */
        .rating-css input:checked ~ label {
            color: #ffc107;
        }
        
        /* LOGIKA HOVER: Bintang yang di-hover & sebelumnya jadi kuning */
        .rating-css label:hover ~ label,
        .rating-css label:hover {
            color: #ffc107;
        }

        /* Container dibalik agar urutan HTML 5-4-3-2-1 tampil visual 1-2-3-4-5 */
        .star-icon {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar sticky-top mb-4">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="/" class="btn-back me-3">
                    <i class="bi bi-arrow-left"></i> Menu
                </a>
                <span class="navbar-brand mb-0">Riwayat Pesanan</span>
            </div>
            
            <div class="d-none d-md-block fw-bold text-secondary">
                Halo, {{ Auth::user()->name }}
            </div>
        </div>
    </nav>

    <div class="container">
        
        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($riwayat->isEmpty())
            {{-- TAMPILAN JIKA KOSONG --}}
            <div class="text-center py-5">
                <div class="empty-state-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <h4 class="fw-bold text-secondary">Belum ada pesanan</h4>
                <p class="text-muted">Yuk pesan makanan enak sekarang!</p>
                <a href="/" class="btn btn-danger rounded-pill px-4 py-2 mt-2 fw-bold" style="background-color: var(--primary-color); border:none;">
                    Pesan Sekarang
                </a>
            </div>
        @else
            {{-- DAFTAR KARTU RIWAYAT --}}
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @foreach($riwayat as $data)
                    <div class="history-card">
                        
                        {{-- Header Kartu --}}
                        <div class="card-header-custom">
                            <div>
                                <div class="trx-code">{{ $data->kode_transaksi }}</div>
                                <div class="trx-date">
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    {{ date('d M Y, H:i', strtotime($data->created_at)) }}
                                </div>
                            </div>
                            @if($data->status == 'dikirim')
                                <div class="alert alert-info mt-3 mb-0" style="background-color: #e0f2fe; border: 1px solid #bae6fd; color: #0284c7;">
                                    🚚 <strong>Pesanan Sedang Dikirim!</strong><br>
                                    Barang sudah diserahkan ke pihak ekspedisi.<br>
                                    Estimasi tiba pada tanggal: <strong>{{ \Carbon\Carbon::parse($data->estimasi_tiba)->translatedFormat('d F Y') }}</strong>
                                </div>
                            @endif
                            @if($data->status == 'pending')
                                <span class="badge-status badge-pending"><i class="bi bi-hourglass-split me-1"></i> Diproses</span>
                            @else
                                <span class="badge-status badge-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                            @endif
                        </div>

                        {{-- Body Kartu --}}
                        <div class="card-body-custom">
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge-method">
                                            @if($data->metode_pembayaran == 'qris')
                                                <i class="bi bi-qr-code me-2"></i> QRIS
                                            @else
                                                <i class="bi bi-cash-stack me-2"></i> Tunai
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-muted small mb-1">Total Pembayaran</div>
                                    <div class="price-total">Rp {{ number_format($data->total_harga, 0, ',', '.') }}</div>
                                </div>

                                <div class="d-flex align-items-center">
                                    @if($data->status == 'selesai')
                                        {{-- Cek sudah review belum --}}
                                        @if(!$data->review)
                                            <button type="button" class="btn btn-review" data-bs-toggle="modal" data-bs-target="#modalReview{{ $data->id }}">
                                                <i class="bi bi-star-fill me-1"></i> Beri Nilai
                                            </button>
                                        @else
                                            <div class="me-2 text-warning fw-bold border border-warning rounded-pill px-3 py-1 bg-light">
                                                <i class="bi bi-star-fill"></i> {{ $data->review->rating }}
                                            </div>
                                        @endif

                                        <a href="{{ route('transaksi.struk', $data->id) }}" target="_blank" class="btn-struk">
                                            <i class="bi bi-file-pdf me-1"></i> Invoice
                                        </a>
                                    @else
                                        <div class="text-muted small fst-italic text-end" style="max-width: 120px;">
                                            Menunggu Konfirmasi Kasir
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL REVIEW --}}
                    @if($data->status == 'selesai' && !$data->review)
                    <div class="modal fade" id="modalReview{{ $data->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title fw-bold">Beri Ulasan Pesanan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                
                                <form action="{{ route('review.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="transaksi_id" value="{{ $data->id }}">
                                    
                                    <div class="modal-body text-center">
                                        <h6 class="fw-bold mb-3">Bagaimana pengalaman makan Anda?</h6>
                                        
                                        {{-- BINTANG RATING (Urutan 5 ke 1 untuk logic CSS Reverse) --}}
                                        <div class="rating-css">
                                            <div class="star-icon">
                                                <input type="radio" value="5" name="rating" id="rating5-{{ $data->id }}">
                                                <label for="rating5-{{ $data->id }}" class="bi bi-star-fill"></label>

                                                <input type="radio" value="4" name="rating" id="rating4-{{ $data->id }}">
                                                <label for="rating4-{{ $data->id }}" class="bi bi-star-fill"></label>

                                                <input type="radio" value="3" name="rating" id="rating3-{{ $data->id }}">
                                                <label for="rating3-{{ $data->id }}" class="bi bi-star-fill"></label>
                                                
                                                <input type="radio" value="2" name="rating" id="rating2-{{ $data->id }}">
                                                <label for="rating2-{{ $data->id }}" class="bi bi-star-fill"></label>
                                                
                                                <input type="radio" value="1" name="rating" id="rating1-{{ $data->id }}" checked>
                                                <label for="rating1-{{ $data->id }}" class="bi bi-star-fill"></label>
                                            </div>
                                        </div>

                                        <div class="mt-3 text-start">
                                            <label class="form-label fw-bold small text-muted">Kritik & Saran (Opsional)</label>
                                            <textarea name="komentar" class="form-control" rows="3" placeholder="Contoh: Harga terjangkau dan rasanya nikmat..."></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger fw-bold px-4" style="background-color: var(--primary-color);">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @endforeach
                </div>
            </div>
        @endif
    </div>
    {{-- ================= FOOTER ================= --}}
    <footer style="background-color: #181818; color: #b0b0b0; font-family: 'Poppins', sans-serif;">
        
        {{-- Garis Merah di Atas --}}
        <div style="height: 5px; background-color: #e4002b; width: 100%;"></div>

        <div class="container py-5">
            <div class="row g-4">
                
                {{-- KOLOM 1: BRAND & INFO --}}
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-family: 'Oswald', sans-serif; letter-spacing: 1px;">
                        <img src="{{ asset('template/img/tahu.png') }}" alt="Logo" width="35" height="35" class="me-2 rounded-circle bg-white p-1">
                        SMOLIE GIFT
                    </h5>
                    <p class="small mb-4" style="line-height: 1.6;">
                        Menyajikan Tahu Lontong & Tahu Tek dengan resep bumbu petis legendaris sejak 2010. Rasakan kenikmatan asli Jawa Timur.
                    </p>
                    
                    <ul class="list-unstyled small">
                        <li class="mb-2 d-flex align-items-start">
                            <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i> 
                            <span>Jl. Sukorejo Indah No. 314, Katang</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-clock-fill text-danger me-2"></i> 
                            <span>Buka: 10.00 - 16.00 WIB</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="bi bi-envelope-fill text-danger me-2"></i> 
                            <span>admin@warungtahu.com</span>
                        </li>
                    </ul>
                </div>

                {{-- KOLOM 2: MENU FAVORIT --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Menu Favorit</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Lontong Biasa</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Telur Spesial</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary hover-text-white">Tahu Tek</a></li>
                        <li><a href="#" class="text-decoration-none text-secondary hover-text-white">Es Campur</a></li>
                    </ul>
                </div>

                {{-- KOLOM 3: LAYANAN --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Layanan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2">Dine-in (Makan di Tempat)</li>
                        <li class="mb-2">Take Away (Bungkus)</li>
                        <li class="mb-2">Menerima Pesanan (Catering)</li>
                    </ul>
                </div>

                {{-- KOLOM 4: IKUTI KAMI & WA --}}
                <div class="col-lg-4 col-md-12">
                    <h6 class="text-white fw-bold mb-3 text-uppercase small">Ikuti Kami</h6>
                    <p class="small mb-3">Dapatkan promo terbaru dan info menarik lewat sosial media kami.</p>
                    
                    <div class="d-flex gap-3 mb-4">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-whatsapp"></i></a>
                    </div>

                    <h6 class="text-white fw-bold mb-2 small text-uppercase" style="font-size: 0.75rem;">PESAN CEPAT VIA WHATSAPP?</h6>
                    @if(\App\Helpers\AdminHelper::hasAdminWhatsApp())
                        <a href="{{ \App\Helpers\AdminHelper::getAdminWhatsAppLink('Saya ingin menanyakan status pesanan') }}" target="_blank" class="btn btn-danger w-100 fw-bold rounded-pill py-2 shadow-sm" style="background-color: #e4002b; border: none;">
                            <i class="bi bi-whatsapp me-2"></i> CHAT SEKARANG
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- COPYRIGHT --}}
        <div class="py-3 text-center border-top border-secondary border-opacity-10" style="background-color: #ffff;">
            <small class="text-muted">&copy; 2026 Smolie Gift. All Rights Reserved.</small>
        </div>
    </footer>

    {{-- Tambahan CSS Khusus untuk Footer Hover Effect --}}
    <style>
        footer a.hover-text-white:hover {
            color: white !important;
            padding-left: 5px;
            transition: all 0.2s ease;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
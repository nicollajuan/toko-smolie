<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Smolie Gift</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    {{-- Font Konsisten dengan Beranda --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- TEMA SMOLIE GIFT --- */
        :root { 
            --primary-color: #DD3827; 
            --secondary-color: #FDE8E5; 
            --bg-light: #F9F9FB; 
            --text-dark: #2D3142; 
        }
        
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: var(--bg-light); 
            color: #4F5665;
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .theme-font {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }

        /* CARD & LAYOUT */
        .card-smolie { 
            border-radius: 20px; 
            border: none; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.03); 
            background: white;
        }

        /* NAVBAR / HEADER KEMBALI */
        .header-cart {
            background-color: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        .btn-back { 
            text-decoration: none; color: #64748B; font-weight: 600; 
            display: inline-flex; align-items: center; transition: 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-back:hover { color: var(--primary-color); transform: translateX(-3px); }

        /* TABEL PRODUK */
        .table-custom th { font-weight: 600; color: #64748B; background-color: white; border-bottom: 2px dashed #eee; padding: 15px; font-family: 'Poppins', sans-serif; }
        .table-custom td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #f8f9fa; }
        .product-note { font-size: 0.8rem; color: #888; margin-top: 4px; display: flex; align-items: start; }
        
        /* KONTROL JUMLAH & CHECKBOX */
        .form-check-input { cursor: pointer; }
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        .qty-control { background: var(--bg-light); border-radius: 50px; padding: 4px; display: inline-flex; align-items: center; border: 1px solid #eee; }
        .btn-qty-cart { width: 30px; height: 30px; border-radius: 50%; border: none; background: white; color: var(--text-dark); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .btn-qty-cart:hover { background: var(--primary-color); color: white; }
        .input-qty-cart { width: 35px; border: none; background: transparent; text-align: center; font-weight: bold; font-size: 0.95rem; color: var(--text-dark); }

        /* INPUT FORM & ALERT */
        .form-control, .form-select { border-radius: 12px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.95rem; background-color: #f8fafc; transition: 0.3s; }
        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(221, 56, 39, 0.1); background-color: white; }
        .input-group-text { border-radius: 12px 0 0 12px; border: none; }
        
        .alert-service { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 15px; display: flex; align-items: center; }
        
        /* TOMBOL AKSI */
        .btn-primary-smolie { background-color: var(--primary-color); color: white; border: none; padding: 12px 20px; border-radius: 50px; font-weight: 600; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(221, 56, 39, 0.2); }
        .btn-primary-smolie:hover { background-color: #C02E1F; color: white; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(221, 56, 39, 0.3); }
        .btn-primary-smolie:disabled { background-color: #cbd5e1; box-shadow: none; transform: none; cursor: not-allowed; }

        .btn-radio-smolie + .btn { border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px; font-weight: 600; color: #64748B; background: white; transition: 0.2s; }
        .btn-radio-smolie:checked + .btn { border-color: var(--primary-color); color: var(--primary-color); background-color: var(--secondary-color); }
    </style>
</head>
<body>

{{-- HEADER KEMBALI --}}
<div class="header-cart sticky-top z-3">
    <div class="container-fluid px-4 px-lg-5">
        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
            <a href="{{ route('transaksi.kasir.menu') }}" class="btn-back fs-5">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Katalog Kasir
            </a>
        @else
            <a href="/" class="btn-back fs-5">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu Utama
            </a>
        @endif
    </div>
</div>

{{-- CONTAINER UTAMA --}}
<div class="container-fluid px-4 px-lg-5 pb-5">
    
    <div class="mb-4">
        <h2 class="fw-bold theme-font">Keranjang Saya</h2>
        <p class="text-muted">Periksa kembali pesanan custom souvenir Anda sebelum checkout.</p>
    </div>

    <div class="row g-4">

        {{-- KOLOM KIRI: DAFTAR PRODUK --}}
        <div class="col-lg-7 mb-4">
            <div class="card card-smolie p-3">

                {{-- NOTIFIKASI --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 small border-0" role="alert" style="background-color: #FFEBEE; color: #C62828;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 small border-0" role="alert" style="background-color: #E8F5E9; color: #2E7D32;">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive custom-scrollbar">
                    <table class="table table-hover align-middle table-custom mb-0" style="min-width: 600px;">
                        <thead>
                            <tr>
                                <th style="width: 5%" class="text-center">
                                    <input class="form-check-input" style="transform: scale(1.2);" type="checkbox" id="checkAll" checked>
                                </th>
                                <th style="width: 40%">Produk</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 20%">Jumlah</th>
                                <th style="width: 15%" class="text-end">Subtotal</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $checkoutSummary = session('checkout_summary');
                                $total_awal = 0;
                            @endphp
                            @if(session('cart'))
                                @foreach(session('cart') as $id => $details)
                                    @php
                                        $subtotal = $details['price'] * $details['quantity'];
                                        $total_awal += $subtotal;
                                    @endphp
                                    <tr data-id="{{ $id }}">
                                        <td class="text-center">
                                            <input class="form-check-input item-checkbox" style="transform: scale(1.2);" type="checkbox"
                                                   value="{{ $id }}" data-subtotal="{{ $subtotal }}" checked>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($details['image'])
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" alt="Foto produk {{ $details['name'] ?? 'pesanan' }}" width="65" height="65" class="rounded-3 me-3 object-fit-cover shadow-sm">
                                                @endif
                                                <div>
                                                    <div class="fw-bold theme-font fs-6">{{ $details['name'] }}</div>
                                                    @if(isset($details['note']) && $details['note'] != '')
                                                        <div class="product-note"><i class="bi bi-pencil-fill me-1 text-warning"></i> {{ $details['note'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <div class="qty-control">
                                                <button class="btn-qty-cart change-qty" data-id="{{ $id }}" data-action="decrease">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text" class="input-qty-cart" value="{{ $details['quantity'] }}" readonly id="qty-disp-{{ $id }}">
                                                <button class="btn-qty-cart change-qty" data-id="{{ $id }}" data-action="increase">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-danger fs-6">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-light text-danger btn-sm remove-from-cart rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; transition: 0.2s;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($checkoutSummary && isset($checkoutSummary['items']))
                                @foreach($checkoutSummary['items'] as $id => $details)
                                    @php
                                        $subtotal = $details['price'] * $details['quantity'];
                                        $total_awal += $subtotal;
                                    @endphp
                                    <tr data-id="{{ $id }}">
                                        <td class="text-center">
                                            <input class="form-check-input item-checkbox" style="transform: scale(1.2);" type="checkbox" value="{{ $id }}" data-subtotal="{{ $subtotal }}" checked disabled>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($details['image'])
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" alt="Foto produk {{ $details['name'] ?? 'pesanan' }}" width="65" height="65" class="rounded-3 me-3 object-fit-cover opacity-75">
                                                @endif
                                                <div>
                                                    <div class="fw-bold theme-font fs-6 opacity-75">{{ $details['name'] }}</div>
                                                    @if(isset($details['note']) && $details['note'] != '')
                                                        <div class="product-note"><i class="bi bi-pencil-fill me-1"></i> {{ $details['note'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted opacity-75">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="text-center fw-bold opacity-75">{{ $details['quantity'] }}</td>
                                        <td class="text-end fw-bold text-danger fs-6 opacity-75">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-light text-secondary btn-sm rounded-circle shadow-none" style="width: 32px; height: 32px;" disabled><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle mb-3" style="width: 80px; height: 80px;">
                                            <i class="bi bi-cart-x fs-1"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2 theme-font">Keranjang Masih Kosong</h5>
                                        <p class="small mb-4">Belum ada souvenir yang Anda tambahkan.</p>
                                        
                                        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                                            <a href="{{ route('transaksi.kasir.menu') }}" class="btn-primary-smolie text-decoration-none px-4">Kembali ke Katalog Kasir</a>
                                        @else
                                            <a href="/" class="btn-primary-smolie text-decoration-none px-4">Mulai Belanja</a>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM PEMBAYARAN --}}
        @if(session('cart') || session('checkout_summary'))
        <div class="col-lg-5">
            @if($checkoutSummary)
                <div class="card card-smolie p-4 mb-4 sticky-top" style="top: 85px;">
                    <h5 class="fw-bold mb-3 theme-font">Ringkasan Checkout Terakhir</h5>
                    <div class="d-flex flex-column flex-md-row align-items-start gap-2 mb-3">
                        <div class="alert alert-success mb-0 flex-grow-1 border-0 small py-2 px-3 rounded-3" role="alert" style="background-color: #E8F5E9; color: #2E7D32;">
                            <i class="bi bi-check-circle-fill me-1"></i> Pesanan berhasil dibuat.
                        </div>
                        @if(!empty($checkoutSummary['transaksi_id']))
                            <a href="{{ route('transaksi.struk', $checkoutSummary['transaksi_id']) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3">
                                <i class="bi bi-printer me-1"></i> Cetak Struk
                            </a>
                        @endif
                    </div>
                    <div class="bg-light p-3 rounded-4 small text-muted">
                        <div class="d-flex justify-content-between mb-2"><span>Total Bayar:</span> <strong class="text-dark">Rp {{ number_format($checkoutSummary['total'], 0, ',', '.') }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Metode:</span> <strong class="text-dark text-uppercase">{{ $checkoutSummary['metode'] }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Pengambilan:</span> <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $checkoutSummary['jenis_pesanan'])) }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span>Nama:</span> <strong class="text-dark">{{ $checkoutSummary['nama_pembeli'] }}</strong></div>
                    </div>
                </div>
            @endif

            @if(!$checkoutSummary || session('cart'))
            <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="selected_items" id="selectedItemsInput">
                <input type="hidden" name="jenis_pesanan" value="{{ session('jenis_pesanan') ?? 'takeaway' }}">

                <div class="card card-smolie p-4 sticky-top" style="top: 85px;">
                    <h5 class="fw-bold mb-4 theme-font">Rincian Pembayaran</h5>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4 border-0 rounded-4 small" style="background-color: #FFEBEE; color: #C62828;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- TOTAL HARGA & DISKON --}}
                    <div class="d-flex flex-column align-items-end mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span class="text-muted fw-bold">Total Bayar</span>
                            <div class="text-end fw-bold fs-4 text-danger theme-font" id="displayTotal">
                                Rp {{ number_format($total_awal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- JENIS PESANAN ALERT --}}
                    @if(session('jenis_pesanan') == 'delivery')
                        <div class="alert-service mb-4">
                            <i class="bi bi-truck fs-3 me-3 text-primary"></i>
                            <div>
                                <strong class="theme-font fs-6">Kirim via Ekspedisi</strong><br>
                                <small class="text-muted">Pesanan akan diantar kurir ke alamat Anda.</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">ALAMAT PENGIRIMAN <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control" rows="2" placeholder="Jalan, RT/RW, Nomor Rumah..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">DETAIL RUMAH (Opsional)</label>
                            <input type="text" name="detail_rumah" class="form-control" placeholder="Contoh: Pagar hitam, depan toko">
                        </div>
                    @elseif(session('jenis_pesanan') == 'dine_in')
                        <div class="alert-service mb-4 border-warning">
                            <i class="bi bi-shop fs-3 me-3 text-warning"></i>
                            <div>
                                <strong class="theme-font fs-6 text-dark">Dine In (Makan Ditempat)</strong><br>
                                <small class="text-muted">Silakan tunggu di meja Anda.</small>
                            </div>
                        </div>
                    @else
                        <div class="alert-service mb-4" style="border-color: #fca5a5; background-color: #fef2f2;">
                            <i class="bi bi-bag-check fs-3 me-3" style="color: var(--primary-color);"></i>
                            <div>
                                <strong class="theme-font fs-6 text-dark">Ambil di Toko (Pickup)</strong><br>
                                <small class="text-muted">Ambil pesanan di toko saat sudah siap.</small>
                            </div>
                        </div>
                    @endif

                    {{-- FORM DATA DIRI & LOGIN CHECK --}}
                    @guest
                        {{-- TAMU / GUEST: Belum Login --}}
                        <div class="alert border-0 rounded-3 py-3 px-3 mb-4 small" style="background-color: #F8FAFC; border-left: 4px solid #3B82F6 !important;">
                            <strong class="text-dark d-block mb-1">Anda Belum Login!</strong>
                            <span class="text-muted">Silakan login terlebih dahulu untuk mengonfirmasi pesanan ini. Pesanan Anda akan tetap aman di keranjang.</span>
                        </div>
                    @else
                        @if(in_array(Auth::user()->usertype, ['admin', 'kasir']))
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NAMA PELANGGAN <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan', 'Pelanggan Toko') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">NOMOR WA PELANGGAN</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-whatsapp"></i></span>
                                    <input type="number" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="form-text small">Nota digital dapat dikirim ke nomor ini.</div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NAMA PEMESAN</label>
                                <input type="text" name="nama_pelanggan" class="form-control" value="{{ Auth::user()->name ?? 'Tamu' }}" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">NOMOR WHATSAPP <span class="text-danger">*</span></label>
                                <div class="input-group shadow-sm" style="border-radius: 12px;">
                                    <span class="input-group-text bg-success text-white"><i class="bi bi-whatsapp"></i></span>
                                    <input type="number" name="no_hp" class="form-control border-start-0" value="{{ Auth::user()->no_hp ?? Auth::user()->whatsapp ?? '' }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="form-text small">Nomor aktif untuk konfirmasi pesanan.</div>
                            </div>
                        @endif
                    @endguest

                    {{-- METODE PEMBAYARAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted mb-2">METODE PEMBAYARAN <span class="text-danger">*</span></label>

                        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                            {{-- KASIR: Tunai/QRIS --}}
                            <div class="d-flex gap-2 mb-3">
                                <div class="w-100">
                                    <input type="radio" class="btn-check btn-radio-smolie" name="metode_pembayaran" id="bayarTunai" value="tunai" checked onclick="toggleQR(false)">
                                    <label class="btn w-100 d-flex flex-column align-items-center justify-content-center" for="bayarTunai">
                                        <i class="bi bi-cash-stack fs-4 mb-1"></i> Tunai
                                    </label>
                                </div>
                                <div class="w-100">
                                    <input type="radio" class="btn-check btn-radio-smolie" name="metode_pembayaran" id="bayarQRIS" value="qris" onclick="toggleQR(true)">
                                    <label class="btn w-100 d-flex flex-column align-items-center justify-content-center" for="bayarQRIS">
                                        <i class="bi bi-qr-code-scan fs-4 mb-1"></i> QRIS
                                    </label>
                                </div>
                            </div>
                            
                            <div id="areaQR" class="alert alert-light border rounded-3 text-center mb-0 small" style="display: none;">
                                <strong><i class="bi bi-phone me-1"></i> Mode QRIS Aktif</strong><br>
                                Pembeli akan diarahkan ke halaman scan.
                            </div>
                            
                            <div id="areaTunai" class="p-3 bg-light rounded-4 border">
                                <div class="mb-2">
                                    <label class="form-label fw-bold small text-muted mb-1">UANG DITERIMA <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold">Rp</span>
                                        <input type="number" name="uang_diterima" id="uangDiterima" class="form-control fw-bold" placeholder="0" required>
                                    </div>
                                    <small id="warningUang" class="text-danger fw-bold mt-1" style="display: none; font-size: 0.75rem;">
                                        <i class="bi bi-exclamation-triangle"></i> Uang kurang!
                                    </small>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-bold small text-muted mb-1">KEMBALIAN</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold border-0">Rp</span>
                                        <input type="text" id="uangKembalian" class="form-control fw-bold text-success bg-white border-0" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- PEMBELI / GUEST: Hanya QRIS --}}
                            <input type="hidden" name="metode_pembayaran" value="qris">
                            <div class="alert border-0 rounded-3 py-2 px-3 small d-flex align-items-center" style="background-color: #FFF8E1; color: #F57F17;">
                                <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                                <div><strong>QRIS Online.</strong> Pembayaran tunai hanya di toko.</div>
                            </div>
                        @endif
                    </div>

                    {{-- TOMBOL CHECKOUT KONDISIONAL --}}
                    @guest
                        <a href="{{ route('login') }}" class="btn-primary-smolie w-100 mt-2 text-center text-decoration-none d-block py-2">
                            Login untuk Konfirmasi Pesanan <i class="bi bi-box-arrow-in-right ms-1 align-middle"></i>
                        </a>
                    @else
                        <button type="submit" class="btn-primary-smolie w-100 mt-2" id="btnCheckout">
                            Konfirmasi Pesanan <i class="bi bi-arrow-right-short fs-5 ms-1 align-middle"></i>
                        </button>
                    @endguest

                </div>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    let currentTotal = 0;

    function hitungKembalian() {
        let uang = parseFloat($('#uangDiterima').val()) || 0;
        let kembalian = uang - currentTotal;

        if ($('#areaTunai').is(':visible') && uang > 0 && kembalian < 0) {
            $('#warningUang').show();
            $('#uangKembalian').val('Kurang!');
            $('#uangKembalian').removeClass('text-success').addClass('text-danger');
            $('#btnCheckout').prop('disabled', true);
        } else {
            $('#warningUang').hide();
            $('#uangKembalian').removeClass('text-danger').addClass('text-success');
            $('#uangKembalian').val(new Intl.NumberFormat('id-ID').format(kembalian > 0 ? kembalian : 0));
            if (currentTotal > 0) $('#btnCheckout').prop('disabled', false);
        }
    }

    function hitungTotal() {
        let total = 0;
        let selectedIds = [];
        $('.item-checkbox:checked').each(function () {
            total += parseFloat($(this).data('subtotal')) || 0;
            selectedIds.push($(this).val());
        });

        currentTotal = total;

        // --- LOGIKA PROMO MEMBER 10% ---
        let isMember = {{ Auth::check() && Auth::user()->usertype == 'user' ? 'true' : 'false' }};
        
        if (isMember && total > 0) {
            let diskon = total * 0.10; // Potongan 10%
            let totalSetelahDiskon = total - diskon;
            currentTotal = totalSetelahDiskon;

            $('#displayTotal').html(
                `<span class="text-muted fs-6 text-decoration-line-through me-2">Rp ${new Intl.NumberFormat('id-ID').format(total)}</span>` +
                `Rp ${new Intl.NumberFormat('id-ID').format(totalSetelahDiskon)} <br>` +
                `<div class="text-success fw-bold text-end mt-1" style="font-size: 0.8rem;"><i class="bi bi-tag-fill"></i> Diskon Member 10% Berhasil Dipasang!</div>`
            );
        } else {
            $('#displayTotal').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
        }

        $('#selectedItemsInput').val(selectedIds.join(','));

        if (selectedIds.length === 0) {
            $('#btnCheckout').prop('disabled', true).html('<i class="bi bi-cart-x me-2"></i> Pilih Produk');
        } else {
            $('#btnCheckout').prop('disabled', false).html('Konfirmasi Pesanan <i class="bi bi-arrow-right-short fs-5 ms-1 align-middle"></i>');
            hitungKembalian();
        }
    }

    function toggleQR(isQris) {
        if (isQris) {
            $('#areaQR').slideDown('fast');
            $('#areaTunai').slideUp('fast');
            $('#uangDiterima').removeAttr('required');
            if (currentTotal > 0) $('#btnCheckout').prop('disabled', false);
        } else {
            $('#areaQR').slideUp('fast');
            $('#areaTunai').slideDown('fast');
            $('#uangDiterima').attr('required', true);
            hitungKembalian();
        }
    }

    $(document).ready(function () {
        $('#uangDiterima').on('input', hitungKembalian);

        // AJAX Update Qty
        $(document).on('click', '.change-qty', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var action = $(this).data('action');
            var input = $("#qty-disp-" + id);
            var newQty = action === "increase" ? parseInt(input.val()) + 1 : parseInt(input.val()) - 1;
            if (newQty < 1) return;

            $.ajax({
                url: '{{ route('update.cart') }}',
                method: "PATCH",
                data: { _token: '{{ csrf_token() }}', id: id, quantity: newQty },
                success: function () { window.location.reload(); }
            });
        });

        // AJAX Delete Item
        $(document).on('click', '.remove-from-cart', function (e) {
            e.preventDefault();
            var id = $(this).parents("tr").attr("data-id");
            if (confirm("Hapus produk ini dari keranjang?")) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: { _token: '{{ csrf_token() }}', id: id },
                    success: function () { window.location.reload(); }
                });
            }
        });

        // Checkbox Logic
        $(document).on('change', '.item-checkbox', function () {
            hitungTotal();
            $('#checkAll').prop('checked', $('.item-checkbox:checked').length === $('.item-checkbox').length);
        });

        $('#checkAll').change(function () {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            hitungTotal();
        });

        hitungTotal();
    });
</script>

</body>
</html>
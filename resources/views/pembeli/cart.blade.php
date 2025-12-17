<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-color: #e4002b; --text-dark: #202124; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .table-custom th { font-weight: 600; color: #555; background-color: #fff; border-bottom: 2px solid #eee; padding: 15px; }
        .table-custom td { padding: 15px; }
        .btn-back { text-decoration: none; color: #555; font-weight: 600; display: flex; align-items: center; }
        .btn-back:hover { color: var(--primary-color); }
        .product-note { font-size: 0.8rem; color: #888; margin-top: 4px; display: flex; align-items: start; }
        
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        
        /* Style Tombol Plus Minus */
        .btn-qty-cart {
            border: 1px solid #ddd; background: white; color: #555;
            width: 32px; height: 32px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; transition: 0.2s;
        }
        .btn-qty-cart:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
        .input-qty-cart {
            width: 40px; border: none; background: transparent; text-align: center; font-weight: bold; font-size: 1rem;
        }
    </style>
</head>
<body>

{{-- NAVBAR (FULL WIDTH) --}}
<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid px-4 px-lg-5">
        <a href="/" class="btn-back fs-5">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
        </a>
        <span class="fw-bold text-dark fs-5">Keranjang Saya</span>
    </div>
</nav>

{{-- CONTAINER FLUID (Agar Full Kanan-Kiri) --}}
<div class="container-fluid px-4 px-lg-5 pb-5">
    <div class="row g-4"> {{-- g-4 memberikan jarak antar kolom --}}
        
        {{-- KOLOM KIRI: DAFTAR PRODUK (Lebar 7/12) --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm p-2">
                
                {{-- NOTIFIKASI --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm m-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm m-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
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
                            @php $total_awal = 0 @endphp
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
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" width="70" height="70" class="rounded-3 me-3 object-fit-cover shadow-sm">
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $details['name'] }}</div>
                                                    @if(isset($details['note']) && $details['note'] != '')
                                                        <div class="product-note"><i class="bi bi-pencil-fill me-1"></i> {{ $details['note'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        
                                        {{-- KOLOM JUMLAH --}}
                                        <td>
                                            <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border">
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
                                            <button class="btn btn-light text-danger btn-sm remove-from-cart rounded-circle shadow-sm" style="width: 35px; height: 35px;"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1 d-block mb-3"></i> 
                                        <h5 class="text-muted">Keranjang Anda kosong</h5>
                                        <a href="/" class="btn btn-danger rounded-pill px-5 mt-2 shadow-sm" style="background-color: var(--primary-color);">Belanja Sekarang</a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM PEMBAYARAN (Lebar 5/12 - LEBIH BESAR) --}}
        @if(session('cart'))
        <div class="col-lg-5">
            <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="selected_items" id="selectedItemsInput">

                <div class="card shadow p-4 sticky-top" style="top: 20px;">
                    <h4 class="fw-bold mb-4 text-dark">Rincian Pembayaran</h4>
                    
                    {{-- TOTAL HARGA --}}
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom">
                        <span class="text-secondary fs-5">Total Bayar</span>
                        <span class="fw-bold fs-3 text-danger" id="displayTotal">
                            Rp {{ number_format($total_awal, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- ================================================= --}}
                    {{-- LOGIKA: FORM ALAMAT (HANYA MUNCUL JIKA DELIVERY) --}}
                    {{-- ================================================= --}}
                    @if(session('jenis_pesanan') == 'delivery')
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4 bg-opacity-10 bg-primary text-primary">
                            <i class="bi bi-truck fs-1 me-3"></i>
                            <div>
                                <strong class="fs-6">Mode Delivery Aktif</strong><br>
                                <small>Pesanan akan diantar kurir ke alamat Anda.</small>
                            </div>
                        </div>

                        {{-- INPUT 1: ALAMAT LENGKAP --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">ALAMAT PENGIRIMAN <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control bg-light" rows="3" placeholder="Jalan, RT/RW, Nomor Rumah..." required></textarea>
                        </div>

                        {{-- INPUT 2: DETAIL/PATOKAN --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">DETAIL RUMAH / PATOKAN</label>
                            <input type="text" name="detail_rumah" class="form-control bg-light" placeholder="Contoh: Pagar hitam, depan toko">
                        </div>
                        <hr class="my-4">
                        
                    @elseif(session('jenis_pesanan') == 'dine_in')
                        <div class="alert alert-warning border-0 d-flex align-items-center mb-4 text-dark">
                            <i class="bi bi-shop fs-2 me-3 text-danger"></i>
                            <div>
                                <strong>Makan Ditempat (Dine In)</strong><br>
                                <small>Silakan tunggu di meja yang tersedia</small>
                            </div>
                        </div>
                        <hr class="my-4">
                        
                    @else
                        {{-- Default Takeaway --}}
                        <div class="alert alert-light border d-flex align-items-center mb-4">
                            <i class="bi bi-bag-check fs-2 me-3 text-danger"></i>
                            <div>
                                <strong>Bawa Pulang (Takeaway)</strong><br>
                                <small>Ambil pesanan di kasir saat sudah siap.</small>
                            </div>
                        </div>
                        <hr class="my-4">
                    @endif
                    {{-- ================================================= --}}

                    {{-- Form Data Diri --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary small">NAMA PEMESAN</label>
                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->name ?? 'Tamu' }}" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small">NOMOR WHATSAPP (AKTIF) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white border-0"><i class="bi bi-whatsapp"></i></span>
                            <input type="number" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="form-text small">Nota pesanan akan dikirim ke nomor ini.</div>
                    </div>

                    {{-- Metode Bayar --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small">METODE PEMBAYARAN <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarTunai" value="tunai" checked onclick="toggleQR(false)">
                            <label class="btn btn-outline-primary w-100 fw-bold py-2" for="bayarTunai">
                                <i class="bi bi-cash-stack me-1"></i> Tunai
                            </label>

                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarQRIS" value="qris" onclick="toggleQR(true)">
                            <label class="btn btn-outline-dark w-100 fw-bold py-2" for="bayarQRIS">
                                <i class="bi bi-qr-code me-1"></i> QRIS
                            </label>
                        </div>
                    </div>

                    <div id="areaQR" class="alert alert-light border text-center mb-3 shadow-sm" style="display: none;">
                        <p class="fw-bold mb-2 small">Scan QRIS Toko:</p>
                        <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS Code" class="img-fluid border rounded shadow-sm mb-2" style="max-width: 180px;">
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow" style="background-color: var(--primary-color); border:none;" id="btnCheckout">
                        Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    
    // 1. UPDATE QUANTITY (PLUS MINUS)
    $(".change-qty").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.data("id");
        var action = ele.data("action");
        var input = $("#qty-disp-" + id);
        var currentQty = parseInt(input.val());
        var newQty = action === "increase" ? currentQty + 1 : currentQty - 1;

        if(newQty < 1) return;

        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "PATCH",
            data: {
                _token: '{{ csrf_token() }}', 
                id: id, 
                quantity: newQty
            },
            success: function (response) {
                window.location.reload();
            }
        });
    });

    // 2. Hitung Total Checkbox
    $(document).ready(function() {
        function hitungTotal() {
            let total = 0;
            let selectedIds = [];
            $('.item-checkbox:checked').each(function() {
                total += parseFloat($(this).data('subtotal'));
                selectedIds.push($(this).val());
            });
            $('#displayTotal').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            $('#selectedItemsInput').val(selectedIds.join(','));
            
            if(selectedIds.length === 0) {
                $('#btnCheckout').prop('disabled', true).text('Pilih Produk Dulu');
            } else {
                $('#btnCheckout').prop('disabled', false).html('Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>');
            }
        }

        $('.item-checkbox').change(function() {
            hitungTotal();
            let allChecked = $('.item-checkbox:checked').length === $('.item-checkbox').length;
            $('#checkAll').prop('checked', allChecked);
        });

        $('#checkAll').change(function() {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            hitungTotal();
        });

        hitungTotal();
    });

    // 3. Hapus Item
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Yakin ingin menghapus produk ini?")) {
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: "DELETE",
                data: { _token: '{{ csrf_token() }}', id: ele.parents("tr").attr("data-id") },
                success: function (response) { window.location.reload(); }
            });
        }
    });

    function toggleQR(show) {
        var qrBox = document.getElementById('areaQR');
        if(show) { qrBox.style.display = 'block'; } else { qrBox.style.display = 'none'; }
    }<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    
    {{-- Bootstrap 5 & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-color: #e4002b; --text-dark: #202124; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .table-custom th { font-weight: 600; color: #555; background-color: #fff; border-bottom: 2px solid #eee; padding: 15px; }
        .table-custom td { padding: 15px; }
        .btn-back { text-decoration: none; color: #555; font-weight: 600; display: flex; align-items: center; }
        .btn-back:hover { color: var(--primary-color); }
        .product-note { font-size: 0.8rem; color: #888; margin-top: 4px; display: flex; align-items: start; }
        
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }
        
        /* Style Tombol Plus Minus */
        .btn-qty-cart {
            border: 1px solid #ddd; background: white; color: #555;
            width: 32px; height: 32px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; transition: 0.2s;
        }
        .btn-qty-cart:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
        .input-qty-cart {
            width: 40px; border: none; background: transparent; text-align: center; font-weight: bold; font-size: 1rem;
        }
    </style>
</head>
<body>

{{-- NAVBAR (FULL WIDTH) --}}
<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid px-4 px-lg-5">
        <a href="/" class="btn-back fs-5">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
        </a>
        <span class="fw-bold text-dark fs-5">Keranjang Saya</span>
    </div>
</nav>

{{-- CONTAINER FLUID --}}
<div class="container-fluid px-4 px-lg-5 pb-5">
    <div class="row g-4">
        
        {{-- KOLOM KIRI: DAFTAR PRODUK (Lebar 7/12) --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm p-2">
                
                {{-- NOTIFIKASI --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm m-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm m-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-custom mb-0">
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
                            @php $total_awal = 0 @endphp
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
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" width="70" height="70" class="rounded-3 me-3 object-fit-cover shadow-sm">
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $details['name'] }}</div>
                                                    @if(isset($details['note']) && $details['note'] != '')
                                                        <div class="product-note"><i class="bi bi-pencil-fill me-1"></i> {{ $details['note'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        
                                        {{-- KOLOM JUMLAH --}}
                                        <td>
                                            <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1 border">
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
                                            <button class="btn btn-light text-danger btn-sm remove-from-cart rounded-circle shadow-sm" style="width: 35px; height: 35px;"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1 d-block mb-3"></i> 
                                        <h5 class="text-muted">Keranjang Anda kosong</h5>
                                        <a href="/" class="btn btn-danger rounded-pill px-5 mt-2 shadow-sm" style="background-color: var(--primary-color);">Belanja Sekarang</a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM PEMBAYARAN (Lebar 5/12) --}}
        @if(session('cart'))
        <div class="col-lg-5">
            <form action="{{ route('checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="selected_items" id="selectedItemsInput">

                <div class="card shadow p-4 sticky-top" style="top: 20px;">
                    <h4 class="fw-bold mb-4 text-dark">Rincian Pembayaran</h4>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom">
                        <span class="text-secondary fs-5">Total Bayar</span>
                        <span class="fw-bold fs-3 text-danger" id="displayTotal">
                            Rp {{ number_format($total_awal, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- =============================================== --}}
                    {{-- 1. LOGIKA JENIS PESANAN (DELIVERY/TAKEAWAY) --}}
                    {{-- =============================================== --}}
                    @if(session('jenis_pesanan') == 'delivery')
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4 bg-opacity-10 bg-primary text-primary">
                            <i class="bi bi-truck fs-1 me-3"></i>
                            <div>
                                <strong class="fs-6">Mode Delivery Aktif</strong><br>
                                <small>Pesanan akan diantar kurir ke alamat Anda.</small>
                            </div>
                        </div>

                        {{-- INPUT ALAMAT LENGKAP (HANYA MUNCUL SAAT DELIVERY) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">ALAMAT PENGIRIMAN <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control bg-light" rows="3" placeholder="Jl. Mawar No. 12, RT/RW..." required></textarea>
                        </div>

                        {{-- INPUT DETAIL RUMAH --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">CATATAN RUMAH / PATOKAN</label>
                            <input type="text" name="detail_rumah" class="form-control bg-light" placeholder="Contoh: Pagar hitam, depan toko">
                        </div>
                        <hr class="my-4">
                    @else
                        {{-- INFO JIKA BUKAN DELIVERY --}}
                        <div class="alert alert-light border d-flex align-items-center mb-4">
                            @if(session('jenis_pesanan') == 'dine_in')
                                <i class="bi bi-shop fs-2 me-3 text-danger"></i>
                                <div><strong>Makan Ditempat (Dine In)</strong><br><small>Silakan tunggu di meja yang tersedia</small></div>
                            @else
                                <i class="bi bi-bag-check fs-2 me-3 text-danger"></i>
                                <div><strong>Bawa Pulang (Takeaway)</strong><br><small>Ambil pesanan di kasir saat sudah siap.</small></div>
                            @endif
                        </div>
                    @endif
                    {{-- =============================================== --}}

                    {{-- Form Data Diri --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">NAMA PEMESAN</label>
                        <input type="text" class="form-control form-control-lg bg-light" value="{{ Auth::user()->name ?? 'Tamu' }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">NOMOR WHATSAPP (AKTIF) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-success text-white border-0"><i class="bi bi-whatsapp"></i></span>
                            <input type="number" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="form-text">Nota pesanan akan dikirim ke nomor ini.</div>
                    </div>

                    {{-- Metode Bayar --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">METODE PEMBAYARAN <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarTunai" value="tunai" checked onclick="toggleQR(false)">
                            <label class="btn btn-outline-primary w-100 py-3 fw-bold rounded-3 border-2" for="bayarTunai">
                                <i class="bi bi-cash-stack me-2 fs-5"></i> Tunai
                            </label>

                            <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarQRIS" value="qris" onclick="toggleQR(true)">
                            <label class="btn btn-outline-dark w-100 py-3 fw-bold rounded-3 border-2" for="bayarQRIS">
                                <i class="bi bi-qr-code me-2 fs-5"></i> QRIS
                            </label>
                        </div>
                    </div>

                    <div id="areaQR" class="alert alert-light border text-center mb-3 shadow-sm" style="display: none;">
                        <p class="fw-bold mb-2">Scan QRIS Toko:</p>
                        <img src="{{ asset('img/qris.jpeg') }}" alt="QRIS Code" class="img-fluid border rounded shadow-sm mb-2" style="max-width: 200px;">
                    </div>
                    
                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow fs-5" style="background-color: var(--primary-color); border:none;" id="btnCheckout">
                        Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    
    // 1. UPDATE QUANTITY
    $(".change-qty").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.data("id");
        var action = ele.data("action");
        var input = $("#qty-disp-" + id);
        var currentQty = parseInt(input.val());
        var newQty = action === "increase" ? currentQty + 1 : currentQty - 1;

        if(newQty < 1) return;

        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "PATCH",
            data: { _token: '{{ csrf_token() }}', id: id, quantity: newQty },
            success: function (response) { window.location.reload(); }
        });
    });

    // 2. Hitung Total Checkbox
    $(document).ready(function() {
        function hitungTotal() {
            let total = 0;
            let selectedIds = [];
            $('.item-checkbox:checked').each(function() {
                total += parseFloat($(this).data('subtotal'));
                selectedIds.push($(this).val());
            });
            $('#displayTotal').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            $('#selectedItemsInput').val(selectedIds.join(','));
            
            if(selectedIds.length === 0) {
                $('#btnCheckout').prop('disabled', true).text('Pilih Produk Dulu');
            } else {
                $('#btnCheckout').prop('disabled', false).html('Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>');
            }
        }

        $('.item-checkbox').change(function() { hitungTotal(); });
        $('#checkAll').change(function() {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            hitungTotal();
        });
        hitungTotal();
    });

    // 3. Hapus Item
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Yakin ingin menghapus produk ini?")) {
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: "DELETE",
                data: { _token: '{{ csrf_token() }}', id: ele.parents("tr").attr("data-id") },
                success: function (response) { window.location.reload(); }
            });
        }
    });

    function toggleQR(show) {
        var qrBox = document.getElementById('areaQR');
        if(show) { qrBox.style.display = 'block'; } else { qrBox.style.display = 'none'; }
    }
</script>

</body>
</html>
</script>

</body>
</html>
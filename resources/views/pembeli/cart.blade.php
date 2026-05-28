<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Smolie Gift</title>
    
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

{{-- NAVBAR --}}
<nav class="navbar navbar-light bg-white shadow-sm mb-4">
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
        <span class="fw-bold text-dark fs-5">Keranjang Saya</span>
    </div>
</nav>

{{-- CONTAINER --}}
<div class="container-fluid px-4 px-lg-5 pb-5">
    <div class="row g-4">

        {{-- KOLOM KIRI: DAFTAR PRODUK --}}
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
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" alt="Foto produk {{ $details['nama_produk'] ?? 'pesanan' }}" width="70" height="70" class="rounded-3 me-3 object-fit-cover shadow-sm">
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
                                            <button class="btn btn-light text-danger btn-sm remove-from-cart rounded-circle shadow-sm" style="width: 35px; height: 35px;">
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
                                            <input class="form-check-input item-checkbox" style="transform: scale(1.2);" type="checkbox"
                                                   value="{{ $id }}" data-subtotal="{{ $subtotal }}" checked disabled>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($details['image'])
                                                    <img src="{{ asset('img/produk/'.$details['image']) }}" alt="Foto produk {{ $details['nama_produk'] ?? 'pesanan' }}" width="70" height="70" class="rounded-3 me-3 object-fit-cover shadow-sm">
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
                                        <td class="text-center fw-bold">{{ $details['quantity'] }}</td>
                                        <td class="text-end fw-bold text-danger fs-6">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-light text-danger btn-sm remove-from-cart rounded-circle shadow-sm" style="width: 35px; height: 35px;" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                                        <h5 class="text-muted mb-3">Keranjang Anda kosong</h5>
                                        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                                            <a href="{{ route('transaksi.kasir.menu') }}" class="btn btn-danger rounded-pill px-5 shadow-sm" style="background-color: var(--primary-color);">Kembali ke Katalog Kasir</a>
                                        @else
                                            <a href="/" class="btn btn-danger rounded-pill px-5 shadow-sm" style="background-color: var(--primary-color);">Belanja Sekarang</a>
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
                <div class="card shadow-sm p-4 mb-4 sticky-top" style="top: 20px;">
                    <h4 class="fw-bold mb-3 text-dark">Ringkasan Checkout Terakhir</h4>
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="alert alert-success mb-0 flex-grow-1" role="alert">
                            Pesanan berhasil dibuat. Berikut ringkasan data checkout terakhir.
                        </div>
                        @if(!empty($checkoutSummary['transaksi_id']))
                            <a href="{{ route('transaksi.struk', $checkoutSummary['transaksi_id']) }}" target="_blank" class="btn btn-outline-primary mb-0">
                                <i class="bi bi-printer me-1"></i> Cetak Struk
                            </a>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Total Bayar:</strong> Rp {{ number_format($checkoutSummary['total'], 0, ',', '.') }}
                    </div>
                    <div class="mb-3">
                        <strong>Metode Pembayaran:</strong> {{ strtoupper($checkoutSummary['metode']) }}
                    </div>
                    <div class="mb-3">
                        <strong>Jenis Pesanan:</strong> {{ ucfirst(str_replace('_', ' ', $checkoutSummary['jenis_pesanan'])) }}
                    </div>
                    @if($checkoutSummary['jenis_pesanan'] === 'delivery')
                        <div class="mb-3">
                            <strong>Alamat Pengiriman:</strong> {{ $checkoutSummary['alamat_pengiriman'] }}
                        </div>
                        <div class="mb-3">
                            <strong>Detail Rumah:</strong> {{ $checkoutSummary['detail_rumah'] ?? '-' }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>Nama Pelanggan:</strong> {{ $checkoutSummary['nama_pembeli'] }}
                    </div>
                    <div class="mb-3">
                        <strong>No. HP:</strong> {{ $checkoutSummary['no_hp'] }}
                    </div>
                    @if(isset($checkoutSummary['items']) && count($checkoutSummary['items']))
                        <div class="mb-3">
                            <strong>Produk di Checkout:</strong>
                            <ul class="ps-3 mb-0">
                                @foreach($checkoutSummary['items'] as $item)
                                    <li>{{ $item['name'] }} x{{ $item['quantity'] }} — Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif
            @if(!$checkoutSummary || session('cart'))
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

                    {{-- JENIS PESANAN --}}
                    @if(session('jenis_pesanan') == 'delivery')
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4 bg-opacity-10 bg-primary text-primary">
                            <i class="bi bi-truck fs-1 me-3"></i>
                            <div>
                                <strong class="fs-6">Mode Delivery Aktif</strong><br>
                                <small>Pesanan akan diantar kurir ke alamat Anda.</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">ALAMAT PENGIRIMAN <span class="text-danger">*</span></label>
                            <textarea name="alamat_pengiriman" class="form-control bg-light" rows="3" placeholder="Jalan, RT/RW, Nomor Rumah..." required></textarea>
                        </div>
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
                        <div class="alert alert-light border d-flex align-items-center mb-4">
                            <i class="bi bi-bag-check fs-2 me-3 text-danger"></i>
                            <div>
                                <strong>Ambil di Toko (Pickup)</strong><br>
                                <small>Ambil pesanan di toko saat sudah siap.</small>
                            </div>
                        </div>
                        <hr class="my-4">
                    @endif

                    {{-- FORM DATA DIRI --}}
                    @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">NAMA PELANGGAN <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pembeli" class="form-control" value="{{ old('nama_pembeli', 'Pelanggan Toko') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small">NOMOR WHATSAPP PELANGGAN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-0"><i class="bi bi-whatsapp"></i></span>
                                <input type="number" name="no_hp" class="form-control" 
                                    value="{{ Auth::user()->no_hp ?? Auth::user()->whatsapp ?? '' }}"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="form-text small">Opsional. Isi jika pelanggan ingin menerima nota digital.</div>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">NAMA PEMESAN</label>
                            <input type="text" class="form-control bg-light" value="{{ Auth::user()->name ?? 'Tamu' }}" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small">NOMOR WHATSAPP (AKTIF) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-0"><i class="bi bi-whatsapp"></i></span>
                                <input type="number" name="no_hp" class="form-control" 
                                    value="{{ Auth::user()->no_hp ?? Auth::user()->whatsapp ?? '' }}"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="form-text small">Nota pesanan akan dikirim ke nomor ini.</div>
                        </div>
                    @endif

                    {{-- METODE PEMBAYARAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary small">METODE PEMBAYARAN <span class="text-danger">*</span></label>

                        @if(Auth::check() && in_array(Auth::user()->usertype, ['admin', 'kasir']))
                            {{-- KASIR: bisa tunai atau QRIS --}}
                            <div class="d-flex gap-3 mb-3">
                                <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarTunai" value="tunai" checked onclick="toggleQR(false)">
                                <label class="btn btn-outline-success w-100 py-3 fw-bold rounded-3 border-2" for="bayarTunai">
                                    <i class="bi bi-cash-stack me-2 fs-5"></i> Tunai
                                </label>
                                <input type="radio" class="btn-check" name="metode_pembayaran" id="bayarQRIS" value="qris" onclick="toggleQR(true)">
                                <label class="btn btn-outline-primary w-100 py-3 fw-bold rounded-3 border-2" for="bayarQRIS">
                                    <i class="bi bi-qr-code-scan me-2 fs-5"></i> QRIS
                                </label>
                            </div>
                            <div id="areaQR" class="alert alert-light border text-center mb-0 shadow-sm" style="display: none;">
                                <p class="fw-bold mb-2 text-dark">Pembayaran QRIS dipilih.</p>
                                <small class="text-muted">Pembeli dapat scan QRIS pada halaman selanjutnya.</small>
                            </div>
                            {{-- TAMBAHKAN KODE INI DI BAWAH AREA QR --}}
                            <div id="areaTunai" class="mt-3 p-3 bg-light rounded-3 border">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-secondary small">UANG DITERIMA DARI PELANGGAN <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold">Rp</span>
                                        <input type="number" name="uang_diterima" id="uangDiterima" class="form-control form-control-lg fw-bold" placeholder="0" required>
                                    </div>
                                    <small id="warningUang" class="text-danger fw-bold mt-1" style="display: none;">
                                        <i class="bi bi-exclamation-triangle"></i> Uang yang diterima kurang!
                                    </small>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label fw-bold text-secondary small">KEMBALIAN</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white fw-bold">Rp</span>
                                        <input type="text" id="uangKembalian" class="form-control form-control-lg fw-bold text-success bg-white" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            {{-- BATAS AKHIR TAMBAHAN --}}
                        @else
                            {{-- PEMBELI: hanya QRIS --}}
                            <input type="hidden" name="metode_pembayaran" value="qris">
                            <div class="alert alert-warning py-3 mb-0">
                                <div class="fw-semibold mb-2"><i class="bi bi-info-circle-fill me-2"></i>QRIS (Pembayaran Online)</div>
                                <small>Pembayaran tunai hanya tersedia jika membeli langsung di toko.</small>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow" style="background-color: var(--primary-color); border:none;" id="btnCheckout">
                        Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                </div>
            </form>
            @else
                <div class="card shadow-sm p-4 sticky-top" style="top: 20px;">
                    <div class="alert alert-info mb-0">
                        Keranjang sudah dikosongkan setelah checkout. Ringkasan pembayaran terakhir ditampilkan di atas.
                    </div>
                </div>
            @endif
        </div>
        @endif

    </div>
</div>

@include('layouts.footer')

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    // ==========================================
    // DEKLARASI FUNGSI GLOBAL
    // ==========================================
    let currentTotal = 0; // akan dihitung dari checkbox terpilih

    // Fungsi 1: Menghitung Kembalian Otomatis
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

    // Fungsi 2: Menghitung Total Belanja (Checkbox)
    function hitungTotal() {
        let total = 0;
        let selectedIds = [];
        $('.item-checkbox:checked').each(function () {
            total += parseFloat($(this).data('subtotal')) || 0;
            selectedIds.push($(this).val());
        });

        currentTotal = total;
        $('#displayTotal').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
        $('#selectedItemsInput').val(selectedIds.join(','));

        if (selectedIds.length === 0) {
            $('#btnCheckout').prop('disabled', true).text('Pilih Produk Dulu');
        } else {
            $('#btnCheckout').prop('disabled', false).html('Konfirmasi Pesanan <i class="bi bi-arrow-right-circle ms-2"></i>');
            hitungKembalian();
        }
    }

    // Fungsi Toggle QRIS / Tunai
    function toggleQR(isQris) {
        if (isQris) {
            $('#areaQR').show();
            $('#areaTunai').hide();
            $('#uangDiterima').removeAttr('required');
            if (currentTotal > 0) $('#btnCheckout').prop('disabled', false);
        } else {
            $('#areaQR').hide();
            $('#areaTunai').show();
            $('#uangDiterima').attr('required', true);
            hitungKembalian();
        }
    }

    // ==========================================
    // JQUERY DOCUMENT READY (Aksi saat halaman dimuat)
    // ==========================================
    $(document).ready(function () {
        
        // A. Trigger Kembalian saat Kasir mengetik nominal
        $('#uangDiterima').on('input', hitungKembalian);

        // B. Update Quantity (AJAX)
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

        // C. Hapus Item (AJAX)
        $(document).on('click', '.remove-from-cart', function (e) {
            e.preventDefault();
            var id = $(this).parents("tr").attr("data-id");
            if (confirm("Yakin ingin menghapus produk ini?")) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: { _token: '{{ csrf_token() }}', id: id },
                    success: function () { window.location.reload(); }
                });
            }
        });

        // D. Event Listener Checkbox Item
        $(document).on('change', '.item-checkbox', function () {
            hitungTotal();
            $('#checkAll').prop('checked', $('.item-checkbox:checked').length === $('.item-checkbox').length);
        });

        // E. Event Listener Checkbox Pilih Semua
        $('#checkAll').change(function () {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            hitungTotal();
        });

        // F. Hitung total saat halaman pertama kali dimuat
        hitungTotal();
    });
</script>

</body>
</html>
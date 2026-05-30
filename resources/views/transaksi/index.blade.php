@extends('layouts.master')
@section('title', 'Riwayat Transaksi')

@section('content')

{{-- STYLE KONSISTEN DENGAN TEMA SMOLIE GIFT --}}
<style>
    :root {
        --smolie-red: #DD3827;
        --text-dark: #202124;
    }
    
    /* Penyederhanaan warna header dan jarak dalam tabel */
    .table-ramah-lansia th {
        font-size: 14px;
        background-color: #f8f9fa; 
        color: #495057;
        padding: 15px; 
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-ramah-lansia td {
        padding: 15px; 
        font-size: 14px;
        color: #333;
        font-family: 'Poppins', sans-serif;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .card-utama {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* Modifikasi Kode TRX agar lebih soft */
    .trx-code {
        font-family: 'Courier New', monospace;
        background: #FFF0ED;
        color: var(--smolie-red);
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 0.85rem;
    }

    /* Tombol Aksi Bulat yang Elegan */
    .btn-aksi-bulat {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        border: none;
    }
    .btn-aksi-bulat:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
    }
</style>

<div class="container-fluid mt-2 px-lg-4">
    
    {{-- 1. HEADER HALAMAN & TOMBOL (DIGABUNG) --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-uppercase fw-bold" style="color: var(--text-dark); font-family: 'Oswald', sans-serif;">
                <i class="bi bi-receipt-cutoff me-2" style="color: var(--smolie-red);"></i>Riwayat Pesanan
            </h2>
            <p class="text-muted fs-6 mb-0">Pantau status pesanan pelanggan secara real-time.</p>
        </div>
        
        {{-- Kelompok Tombol Aksi disatukan di kanan atas --}}
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            @if(in_array(Auth::user()->usertype, ['admin', 'kasir']))
                <a href="{{ route('transaksi.kasir.menu') }}" class="btn text-white rounded-pill px-3 fw-bold d-flex align-items-center shadow-sm" style="background-color: var(--smolie-red);">
                    <i class="bi bi-bag-plus me-1"></i> Buka Katalog Kasir
                </a>
            @endif
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border-0" role="alert" style="background-color: #E8F5E9; color: #2E7D32;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border-0" role="alert" style="background-color: #FFEBEE; color: #C62828;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 2. TABEL DATA --}}
    <div class="card card-utama">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0 table-ramah-lansia" id="tabel-transaksi" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th style="width: 15%">Kode & Waktu</th>
                            <th style="width: 15%">Pelanggan</th>
                            <th style="width: 25%">Layanan & Info</th>
                            <th class="text-center" style="width: 10%">Metode</th>
                            <th style="width: 12%">Total Bayar</th>
                            <th style="width: 10%">Status</th>
                            <th class="text-center" style="width: 8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataTransaksi as $data)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            
                            {{-- Kode TRX & Tanggal --}}
                            <td>
                                <div class="mb-1"><span class="trx-code">{{ $data->kode_transaksi }}</span></div>
                                <small class="text-muted fw-semibold" style="font-size: 0.75rem;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ date('d M Y, H:i', strtotime($data->created_at)) }}
                                </small>
                            </td>
                            
                            {{-- Info Pembeli --}}
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 15px;">{{ $data->nama_pembeli }}</div>
                                <small class="text-muted d-flex align-items-center mt-1" style="font-size: 13px;">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $data->no_hp ?? '-' }}
                                </small>
                            </td>

                            {{-- KOLOM LAYANAN --}}
                            <td>
                                @if($data->jenis_pesanan == 'delivery')
                                    <span class="badge bg-danger rounded-pill px-2 py-1 mb-1 shadow-sm"><i class="bi bi-truck me-1"></i> Delivery</span>
                                    <div class="bg-light p-2 rounded-2 mt-1 small" style="border: 1px dashed #ccc; font-size: 12px; line-height: 1.4;">
                                        <strong class="text-dark d-block">Tujuan:</strong> {{ Str::limit($data->alamat_pengiriman, 40) }}
                                        @if($data->detail_rumah)
                                            <div class="text-muted mt-1 fst-italic">Note: {{ $data->detail_rumah }}</div>
                                        @endif
                                    </div>
                                @elseif($data->jenis_pesanan == 'dine_in')
                                    <span class="badge bg-dark text-white rounded-pill px-2 py-1 shadow-sm"><i class="bi bi-shop me-1"></i> Dine In</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1 shadow-sm"><i class="bi bi-bag me-1"></i> Takeaway</span>
                                @endif

                                @if($data->catatan)
                                    <div class="mt-1 small text-muted fst-italic" style="font-size: 12px;">
                                        <i class="bi bi-pencil-square"></i> Note: {{ $data->catatan }}
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Metode Pembayaran --}}
                            <td class="text-center">
                                @if($data->metode_pembayaran == 'qris')
                                    <span class="badge bg-white text-dark border shadow-sm px-2 py-1"><i class="bi bi-qr-code text-primary"></i> QRIS</span>
                                @else
                                    <span class="badge bg-success shadow-sm px-2 py-1"><i class="bi bi-cash"></i> TUNAI</span>
                                @endif
                            </td>

                            {{-- Total Harga --}}
                            <td>
                                <div class="fw-bold" style="color: var(--smolie-red); font-size: 16px;">
                                    Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                </div>
                            </td>
                            
                            {{-- Status --}}
                            <td>
                                @if($data->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-2 shadow-sm">Pending</span>
                                @elseif($data->status == 'dikirim')
                                    <span class="badge bg-info text-white rounded-pill px-2 shadow-sm"><i class="bi bi-truck me-1"></i> Dikirim</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-2 shadow-sm"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                @endif
                                
                                @if($data->metode_pembayaran == 'qris')
                                    <div class="mt-1">
                                        @if($data->status_pembayaran === 'berhasil')
                                            <span class="badge bg-success text-white" style="font-size: 10px;">Bayar OK</span>
                                        @elseif($data->status_pembayaran === 'gagal')
                                            <span class="badge bg-danger" style="font-size: 10px;">Gagal</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 10px;">Menunggu</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    
                                    {{-- Lihat Bukti (QRIS) --}}
                                    @if($data->metode_pembayaran == 'qris' && $data->bukti_pembayaran)
                                        <a href="javascript:void(0)" onclick="showProofModal({{ $data->id }}, '{{ $data->bukti_pembayaran }}', '{{ $data->status_pembayaran }}')" class="btn btn-info btn-aksi-bulat text-white" title="Lihat Bukti">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                    @endif

                                    {{-- WhatsApp --}}
                                    @php
                                        $nomor = $data->no_hp;
                                        if(substr($nomor, 0, 1) == '0') { $nomor = '62' . substr($nomor, 1); }
                                        $pesan = "Halo kak *" . $data->nama_pembeli . "*!%0a%0aPesanan: *" . strtoupper(str_replace('_', ' ', $data->jenis_pesanan)) . "*%0aKode: " . $data->kode_transaksi . "%0aTotal: Rp " . number_format($data->total_harga, 0, ',', '.') . "%0a%0a";
                                        if($data->jenis_pesanan == 'delivery') { $pesan .= "Alamat: " . $data->alamat_pengiriman . "%0a"; }
                                        $pesan .= "Mohon ditunggu ya! 🎁";
                                        $linkWA = "https://wa.me/$nomor?text=$pesan";
                                    @endphp
                                    @if($data->no_hp)
                                        <a href="{{ $linkWA }}" target="_blank" class="btn btn-success btn-aksi-bulat" title="Chat Customer">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif

                                    {{-- Selesai / Cetak Struk --}}
                                    @if($data->status != 'selesai')
                                        <form action="{{ route('transaksi.selesai', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Selesaikan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-aksi-bulat" title="Selesaikan Order" style="background-color: var(--smolie-red); border:none;">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('transaksi.struk', $data->id) }}" target="_blank" class="btn btn-warning btn-aksi-bulat text-dark" title="Cetak Struk">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                    @endif

                                    {{-- Form Pengiriman (Dibuat lebih ringkas) --}}
                                    @if($data->status != 'selesai' && $data->status != 'dikirim' && $data->jenis_pesanan == 'delivery')
                                        <div class="w-100 mt-1">
                                            <form action="{{ route('admin.transaksi.kirim', $data->id) }}" method="POST">
                                                @csrf
                                                <div class="input-group input-group-sm shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                    <input type="date" name="estimasi_tiba" class="form-control border-0 bg-light" required title="Estimasi Tiba">
                                                    <button type="submit" class="btn btn-info text-white fw-bold border-0" title="Kirim Ekspedisi"><i class="bi bi-send-fill"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL LIHAT BUKTI PEMBAYARAN --}}
<div class="modal fade" id="proofModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-light border-0 px-4 pt-4 pb-3">
                <h5 class="modal-title fw-bold" style="font-family: 'Poppins', sans-serif;">Bukti Pembayaran QRIS</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div id="proofContent"></div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 pb-4 pt-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                <div class="d-flex gap-2">
                    <a id="downloadLink" href="#" class="btn btn-dark rounded-pill px-4 fw-bold d-flex align-items-center">
                        <i class="bi bi-download me-2"></i> Unduh
                    </a>
                    <form id="verifyForm" method="POST" class="m-0">
                        @csrf
                        <button type="button" class="btn btn-danger rounded-pill fw-bold px-3" onclick="verifyPayment('gagal')" style="background-color: #dc3545; border:none;">
                            Tolak
                        </button>
                        <button type="button" class="btn btn-success rounded-pill fw-bold px-4" onclick="verifyPayment('berhasil')" style="background-color: #198754; border:none;">
                            Setujui
                        </button>
                        <input type="hidden" name="status_pembayaran" id="statusInput" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabel-transaksi')) {
            $('#tabel-transaksi').DataTable().destroy();
        }
        $('#tabel-transaksi').DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [[ 1, "desc" ]], // Urutkan berdasarkan waktu terbaru
            "language": {
                "search": "Cari Transaksi:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Transaksi tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "paginate": { "next": ">", "previous": "<" }
            }
        });
        $('.modal').appendTo("body");
    });

    function showProofModal(transaksiId, fileName, status) {
        const modal = new bootstrap.Modal(document.getElementById('proofModal'));
        const proofContent = document.getElementById('proofContent');
        const verifyForm = document.getElementById('verifyForm');
        
        verifyForm.action = `/admin/pembayaran/${transaksiId}/verify`;
        document.getElementById('downloadLink').href = `/pembayaran/${transaksiId}/download-proof`;

        const extension = fileName.split('.').pop().toLowerCase();
        const filePath = `/storage/bukti_pembayaran/${fileName}`;
        let previewHtml = '';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            previewHtml = `<img src="${filePath}" alt="Bukti Pembayaran" class="img-fluid rounded-3 shadow-sm border" style="max-height: 450px; object-fit: contain;">`;
        } else if (extension === 'pdf') {
            previewHtml = `<div class="p-5 bg-white border rounded-3"><i class="bi bi-file-pdf text-danger" style="font-size: 80px;"></i><p class="mt-3 fw-bold text-muted mb-0">Dokumen PDF</p></div>`;
        }

        let statusBadge = '';
        if (status === 'berhasil') statusBadge = '<div class="mb-3"><span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Telah Diverifikasi</span></div>';
        else if (status === 'gagal') statusBadge = '<div class="mb-3"><span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Ditolak</span></div>';
        else statusBadge = '<div class="mb-3"><span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-clock me-1"></i> Menunggu Verifikasi</span></div>';

        proofContent.innerHTML = statusBadge + previewHtml;
        modal.show();
    }

    function verifyPayment(status) {
        document.getElementById('statusInput').value = status;
        document.getElementById('verifyForm').submit();
    }
</script>
@endpush
@endsection
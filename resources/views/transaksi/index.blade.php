@extends('layouts.master')
@section('title', 'Riwayat Transaksi')

@section('content')

{{-- STYLE KHUSUS KFC THEME --}}
<style>
    :root {
        --kfc-red: #E4002B;
        --kfc-black: #202124;
        --gray-bg: #f8f9fa;
    }
    body { background-color: var(--gray-bg); }
    
    .text-kfc { color: var(--kfc-red) !important; }
    
    .card-kfc {
        border: none;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .btn-back-kfc {
        border: 2px solid #e0e0e0;
        color: var(--kfc-black);
        font-weight: 700;
        border-radius: 50px;
        padding: 8px 20px;
        transition: all 0.3s;
    }
    .btn-back-kfc:hover {
        border-color: var(--kfc-black);
        background-color: var(--kfc-black);
        color: white;
    }

    .table-kfc thead th {
        background-color: white;
        color: #999;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 2px solid #eee;
        padding: 1.5rem 1rem;
    }
    
    .table-kfc tbody td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        background-color: white;
    }
    
    .trx-code {
        font-family: 'Courier New', monospace;
        letter-spacing: -0.5px;
        background: #fff5f5;
        color: var(--kfc-red);
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
    }

    .badge-service {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: 800;
    }
    
    .btn-action-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s;
        border: none;
    }
    .btn-action-circle:hover { transform: scale(1.1); }
</style>

<div class="container py-5">
    
    {{-- HEADER SECTION --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1" style="color: var(--kfc-black);">
                <i class="bi bi-receipt-cutoff text-kfc me-2"></i>RIWAYAT PESANAN
            </h2>
            <p class="text-muted mb-0">Pantau status pesanan pelanggan secara real-time.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ url('/home') }}" class="btn btn-back-kfc">
                <i class="bi bi-arrow-left"></i> KEMBALI
            </a>
        </div>
    </div>

    {{-- CARD UTAMA --}}
    <div class="card card-kfc">
        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- TABEL UTAMA --}}
                <table class="table table-kfc table-hover mb-0" id="tabel-transaksi">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">Kode & Waktu</th>
                            <th width="20%">Pelanggan</th>
                            <th width="20%">Layanan & Info</th>
                            <th width="10%">Metode</th>
                            <th width="15%">Total Bayar</th>
                            <th width="10%">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataTransaksi as $data)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            
                            {{-- Kode TRX & Tanggal --}}
                            <td>
                                <div class="mb-1"><span class="trx-code">{{ $data->kode_transaksi }}</span></div>
                                <small class="text-muted fw-semibold" style="font-size: 0.8rem;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ date('d M, H:i', strtotime($data->created_at)) }}
                                </small>
                            </td>
                            
                            {{-- Info Pembeli --}}
                            <td>
                                <h6 class="mb-0 fw-bold text-dark">{{ $data->nama_pembeli }}</h6>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-whatsapp text-success"></i> {{ $data->no_hp ?? '-' }}
                                </small>
                            </td>

                            {{-- KOLOM LAYANAN (Dine In/Takeaway/Delivery) --}}
                            <td>
                                @if($data->jenis_pesanan == 'delivery')
                                    <span class="badge badge-service bg-danger mb-2">
                                        <i class="bi bi-truck me-1"></i> Delivery
                                    </span>
                                    {{-- Info Alamat Delivery --}}
                                    <div class="p-2 rounded border bg-light small" style="line-height: 1.3; font-size: 0.75rem;">
                                        <strong class="text-dark d-block mb-1">Tujuan:</strong>
                                        {{ Str::limit($data->alamat_pengiriman, 40) }}
                                        @if($data->detail_rumah)
                                            <div class="text-muted mt-1 fst-italic">Note: {{ $data->detail_rumah }}</div>
                                        @endif
                                    </div>

                                @elseif($data->jenis_pesanan == 'dine_in')
                                    <span class="badge badge-service bg-dark text-white">
                                        <i class="bi bi-shop me-1"></i> Dine In
                                    </span>

                                @else
                                    {{-- Default Takeaway --}}
                                    <span class="badge badge-service bg-warning text-dark">
                                        <i class="bi bi-bag me-1"></i> Takeaway
                                    </span>
                                @endif
                            </td>
                            
                            {{-- Metode Pembayaran --}}
                            <td>
                                @if($data->metode_pembayaran == 'qris')
                                    <span class="badge rounded-1 border border-dark text-dark bg-white"><i class="bi bi-qr-code"></i> QRIS</span>
                                @else
                                    <span class="badge rounded-1 bg-success"><i class="bi bi-cash"></i> TUNAI</span>
                                @endif
                            </td>

                            {{-- Total Harga --}}
                            <td>
                                <h5 class="mb-0 fw-bold text-kfc" style="letter-spacing: -0.5px;">
                                    Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                </h5>
                            </td>
                            
                            {{-- Status --}}
                            <td>
                                @if($data->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3"><i class="bi bi-check-circle me-1"></i> Selesai</span>
                                @endif
                                
                                {{-- Status Pembayaran (QRIS) --}}
                                @if($data->metode_pembayaran == 'qris')
                                    <br><small class="mt-1 d-block">
                                        @if($data->status_pembayaran === 'berhasil')
                                            <span class="badge bg-success text-white">Pembayaran OK</span>
                                        @elseif($data->status_pembayaran === 'gagal')
                                            <span class="badge bg-danger">Pembayaran Gagal</span>
                                        @else
                                            <span class="badge bg-info">Menunggu Bayar</span>
                                        @endif
                                    </small>
                                @endif
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    
                                    {{-- TOMBOL LIHAT BUKTI PEMBAYARAN (QRIS) --}}
                                    @if($data->metode_pembayaran == 'qris' && $data->bukti_pembayaran)
                                        <a href="javascript:void(0)" 
                                           onclick="showProofModal({{ $data->id }}, '{{ $data->bukti_pembayaran }}', '{{ $data->status_pembayaran }}')"
                                           class="btn btn-info btn-action-circle shadow-sm text-white" 
                                           title="Lihat Bukti Pembayaran">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                    @endif

                                    {{-- LOGIKA WHATSAPP (SAMA PERSIS DENGAN KODE ANDA) --}}
                                    @php
                                        $nomor = $data->no_hp;
                                        if(substr($nomor, 0, 1) == '0') {
                                            $nomor = '62' . substr($nomor, 1);
                                        }
                                        
                                        $pesan = "Halo kak *" . $data->nama_pembeli . "*! 👋%0a%0a";
                                        $pesan .= "Pesanan: *" . strtoupper(str_replace('_', ' ', $data->jenis_pesanan)) . "*%0a"; 
                                        $pesan .= "Kode: " . $data->kode_transaksi . "%0a";
                                        $pesan .= "Total: Rp " . number_format($data->total_harga, 0, ',', '.') . "%0a%0a";
                                        
                                        if($data->jenis_pesanan == 'delivery') {
                                            $pesan .= "Alamat: " . $data->alamat_pengiriman . "%0a";
                                        }

                                        $pesan .= "Mohon ditunggu ya! 🍳";
                                        $linkWA = "https://wa.me/$nomor?text=$pesan";
                                    @endphp

                                    {{-- TOMBOL KIRIM NOTA --}}
                                    @if($data->no_hp)
                                        <a href="{{ $linkWA }}" target="_blank" class="btn btn-success btn-action-circle shadow-sm" title="Chat Customer">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif

                                    {{-- TOMBOL SELESAI / CETAK STRUK --}}
                                    @if($data->status == 'pending')
                                        <form action="{{ route('transaksi.selesai', $data->id) }}" method="POST" onsubmit="return confirm('Selesaikan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-action-circle shadow-sm" title="Selesaikan Order">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('transaksi.struk', $data->id) }}" target="_blank" class="btn btn-warning btn-action-circle shadow-sm text-dark" title="Cetak Struk">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Footer Tabel (Opsional jika ingin pagination) --}}
            <div class="card-footer bg-white border-top-0 py-3"></div>
        </div>
    </div>
</div>

{{-- MODAL LIHAT BUKTI PEMBAYARAN --}}
<div class="modal fade" id="proofModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">Bukti Pembayaran QRIS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="proofContent"></div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a id="downloadLink" href="#" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download
                </a>
                <form id="verifyForm" method="POST" style="display: inline;">
                    @csrf
                    <div class="btn-group ms-2" role="group">
                        <button type="button" class="btn btn-success" onclick="verifyPayment('berhasil')">
                            <i class="bi bi-check-circle me-1"></i>Setujui
                        </button>
                        <button type="button" class="btn btn-danger" onclick="verifyPayment('gagal')">
                            <i class="bi bi-x-circle me-1"></i>Tolak
                        </button>
                    </div>
                    <input type="hidden" name="status_pembayaran" id="statusInput" value="">
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showProofModal(transaksiId, fileName, status) {
        const modal = new bootstrap.Modal(document.getElementById('proofModal'));
        const proofContent = document.getElementById('proofContent');
        const downloadLink = document.getElementById('downloadLink');
        const verifyForm = document.getElementById('verifyForm');
        const statusInput = document.getElementById('statusInput');

        // Set form action
        verifyForm.action = `/admin/pembayaran/${transaksiId}/verify`;
        downloadLink.href = `/pembayaran/${transaksiId}/download-proof`;

        // Determine file type and show preview
        const extension = fileName.split('.').pop().toLowerCase();
        const filePath = `/storage/bukti_pembayaran/${fileName}`;

        let previewHtml = '';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
            previewHtml = `
                <div class="text-center mb-3">
                    <img src="${filePath}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            `;
        } else if (extension === 'pdf') {
            previewHtml = `
                <div class="text-center mb-3">
                    <i class="bi bi-file-pdf text-danger" style="font-size: 64px;"></i>
                    <p class="mt-2 text-muted">File PDF</p>
                </div>
            `;
        } else {
            previewHtml = `
                <div class="alert alert-info">
                    <i class="bi bi-file-earmark me-2"></i>File: ${fileName}
                </div>
            `;
        }

        // Add status badge
        let statusBadge = '';
        if (status === 'berhasil') {
            statusBadge = '<span class="badge bg-success mb-3"><i class="bi bi-check-circle me-1"></i> Telah Diverifikasi</span>';
        } else if (status === 'gagal') {
            statusBadge = '<span class="badge bg-danger mb-3"><i class="bi bi-x-circle me-1"></i> Ditolak</span>';
        } else {
            statusBadge = '<span class="badge bg-warning mb-3"><i class="bi bi-clock me-1"></i> Menunggu Verifikasi</span>';
        }

        proofContent.innerHTML = statusBadge + previewHtml;
        modal.show();
    }

    function verifyPayment(status) {
        document.getElementById('statusInput').value = status;
        document.getElementById('verifyForm').submit();
    }
</script>
@endsection
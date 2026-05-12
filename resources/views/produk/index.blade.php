@extends('layouts.master')
@section('title', 'Menu Kami')

@section('content')

{{-- CSS KHUSUS RAMAH LANSIA (ELDERLY-FRIENDLY) --}}
<style>
    .table-ramah-lansia th {
        font-size: 16px;
        background-color: #ffeaea; /* Merah sangat muda agar tidak silau */
        color: #b8001f;
        padding: 18px 15px;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
    }

    .table-ramah-lansia td {
        padding: 18px 15px;
        font-size: 16px;
        color: #333;
        font-family: 'Poppins', sans-serif;
    }

    .img-produk-besar {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border: 2px solid #f1f3f5;
    }

    .text-nama-produk {
        font-size: 18px;
        font-weight: 700;
        color: #202124;
        letter-spacing: 0.5px;
    }

    .btn-aksi-jelas {
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    
    /* Efek hanya bergerak naik tanpa ganti warna */
.btn-aksi-jelas:hover {
    transform: translateY(-5px) !important; /* Gerak naik */
    background-color: transparent !important; /* Tetap transparan */
    color: inherit !important; /* Warna teks tetap */
    box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Bayangan halus agar gerak terasa */
}
</style>

<div class="container-fluid mt-2 px-lg-4">
    
    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 text-uppercase fw-bold" style="color: #202124; font-family: 'Oswald', sans-serif;">Daftar Produk</h2>
            <p class="text-muted fs-6">Kelola menu makanan dan minuman warung Anda.</p>
        </div>
        
        <div>
            <button class="btn btn-primary shadow-sm px-4 py-2 fw-bold fs-5" data-bs-toggle="modal" data-bs-target="#modalTambahProduk" style="background-color: #E4002B; border: none; border-radius: 12px;"> 
                <i class="bi bi-plus-lg me-2"></i> Tambah Menu Baru
            </button>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm fs-5" role="alert" style="border-left: 5px solid #E4002B;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 3. TOOLBAR --}}
    <div class="card mb-4 p-3 d-flex flex-row gap-2 bg-white border-0 shadow-sm" style="border-radius: 12px;">
        <a href="{{ url('/home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-bold fs-6 d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('produk.excel') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold fs-6 d-flex align-items-center" style="background-color: #198754; border:none;">
                <i class="bi bi-file-earmark-excel me-2"></i> Excel
            </a>
            <a href="{{ route('produk.pdf') }}" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold fs-6 d-flex align-items-center" style="background-color: #dc3545; border:none;">
                <i class="bi bi-file-earmark-pdf me-2"></i> PDF
            </a>
            @if(Route::has('produk.chart'))
                <a href="{{ route('produk.chart') }}" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold fs-6 d-flex align-items-center">
                    <i class="bi bi-graph-up me-2"></i> Chart
                </a> 
            @endif
        </div>
    </div>

    {{-- 4. TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-ramah-lansia border-top" id="tabel-produk" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Info Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th class="text-center">Status</th> 
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataProduk as $data)
                        <tr class="{{ $data->status == 'non-aktif' ? 'table-secondary bg-opacity-25' : '' }}">
                            <td class="text-center fw-bold text-muted fs-5">{{ $loop->iteration }}</td>
                            
                            {{-- Info Produk --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        @if($data->gambar)
                                            <img src="{{ asset('img/produk/'.$data->gambar) }}" class="img-produk-besar">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center text-muted img-produk-besar" style="border: 2px dashed #ccc;">
                                                <i class="bi bi-image" style="font-size: 2rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-nama-produk text-uppercase" style="font-family:'Oswald', sans-serif;">{{ $data->nama_produk }}</div>
                                        <div class="text-muted mt-1" style="font-size: 14px;">ID Produk: <strong class="font-monospace text-dark">{{ $data->id }}</strong></div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Kategori --}}
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 shadow-sm" style="font-size: 14px; font-weight: 500;">
                                    {{ optional($data->kategori)->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            
                            {{-- Harga --}}
                            <td class="fw-bold" style="color: #E4002B; font-size: 20px; letter-spacing: 0.5px;">
                                Rp {{ number_format($data->harga, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                @if($data->status == 'non-aktif')
                                    <span class="badge bg-secondary border shadow-sm px-3 py-2" style="font-size: 14px; letter-spacing: 1px;">NON-AKTIF</span>
                                @else
                                    <span class="badge bg-success border shadow-sm px-3 py-2" style="font-size: 14px; letter-spacing: 1px;">AKTIF</span>
                                @endif
                            </td>
                            
                            {{-- Stok --}}
                            <td class="text-center">
                                @if($data->stock <= 5)
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-2 fs-6 shadow-sm">{{ $data->stock }}</span>
                                @else
                                    <strong style="font-size: 20px; color: #202124;">{{ $data->stock }}</strong>
                                @endif
                            </td>
                            
                            {{-- Aksi (DIPERBESAR AGAR JELAS) --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary btn-aksi-jelas" data-bs-toggle="modal" data-bs-target="#modalEditProduk{{ $data->id }}">
                                        <i class="bi bi-pencil-square fs-5"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-aksi-jelas" data-bs-toggle="modal" data-bs-target="#modalHapusProduk{{ $data->id }}">
                                        <i class="bi bi-trash fs-5"></i> Hapus
                                    </button>
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

{{-- MODALS INCLUDE (Tetap aman tanpa diubah) --}}
@foreach ($dataProduk as $data)
    @include('produk.edit', ['data' => $data])
    @include('produk.delete', ['data' => $data])
@endforeach
@include('produk.create') 

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabel-produk')) {
            $('#tabel-produk').DataTable().destroy();
        }

        $('#tabel-produk').DataTable({
            "responsive": true,
            "autoWidth": false,
            "stateSave": true,
            "language": {
                "search": "Cari Menu:",
                "lengthMenu": "Tampilkan _MENU_ menu",
                "zeroRecords": "Menu tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": { "next": ">", "previous": "<" }
            }
        });
        
        $('.modal').appendTo("body");
    });
</script>
@endpush
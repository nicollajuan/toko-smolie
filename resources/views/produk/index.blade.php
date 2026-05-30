@extends('layouts.master')
@section('title', 'Menu Kami')

@section('content')

<style>
    /* Penyederhanaan warna header dan jarak dalam tabel */
    .table-ramah-lansia th {
        font-size: 15px;
        background-color: #f8f9fa; /* Diubah menjadi abu-abu netral agar lebih clean */
        color: #495057;
        padding: 15px; /* Jarak disesuaikan */
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        border-bottom: 2px solid #e9ecef;
    }

    .table-ramah-lansia td {
        padding: 15px; /* Jarak disesuaikan */
        font-size: 15px;
        color: #333;
        font-family: 'Poppins', sans-serif;
        vertical-align: middle; /* Memastikan isi tabel selalu di tengah secara vertikal */
    }

    .img-produk-besar {
        width: 70px; /* Ukuran gambar diperkecil sedikit agar tidak terlalu memakan ruang */
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }

    .text-nama-produk {
        font-size: 16px;
        font-weight: 700;
        color: #202124;
        margin-bottom: 2px;
    }

    .btn-aksi-jelas {
        border-radius: 8px; /* Ujung sedikit tidak terlalu membulat */
        padding: 6px 12px; /* Ukuran tombol disesuaikan */
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
    }
    
    .btn-aksi-jelas:hover {
        transform: translateY(-2px) !important; 
        background-color: transparent !important; 
        color: inherit !important; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
    }
</style>

<div class="container-fluid mt-2 px-lg-4">
    
    {{-- 1. HEADER HALAMAN & TOMBOL (DIGABUNG) --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-uppercase fw-bold" style="color: #202124; font-family: 'Oswald', sans-serif;">Daftar Produk</h2>
            <p class="text-muted fs-6 mb-0">Kelola produk souvenir di toko Anda.</p>
        </div>
        
        {{-- Kelompok Tombol Aksi & Export disatukan di kanan atas --}}
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('produk.excel') }}" class="btn btn-success rounded-pill px-3 fw-bold d-flex align-items-center" style="background-color: #198754; border:none;">
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
            <a href="{{ route('produk.pdf') }}" class="btn btn-danger rounded-pill px-3 fw-bold d-flex align-items-center" style="background-color: #dc3545; border:none;">
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
            @if(Route::has('produk.chart'))
                <a href="{{ route('produk.chart') }}" class="btn btn-dark rounded-pill px-3 fw-bold d-flex align-items-center">
                    <i class="bi bi-graph-up me-1"></i> Chart
                </a> 
            @endif
            <button class="btn btn-primary shadow-sm px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahProduk" style="background-color: #E4002B; border: none; border-radius: 20px;"> 
                <i class="bi bi-plus-lg me-1"></i> Tambah Menu Baru
            </button>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm fs-6" role="alert" style="border-left: 4px solid #E4002B;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0"> {{-- Menghilangkan padding card-body agar tabel menyatu dengan card --}}
            <div class="table-responsive p-3"> {{-- Memberikan padding khusus untuk area tabel/search bar DataTables --}}
                <table class="table table-hover align-middle mb-0 table-ramah-lansia" id="tabel-produk" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 35%;">Info Produk</th>
                            <th style="width: 15%;">Kategori</th>
                            <th style="width: 15%;">Harga</th>
                            <th class="text-center" style="width: 10%;">Status</th> 
                            <th class="text-center" style="width: 5%;">Stok</th>
                            <th class="text-center" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataProduk as $data)
                        <tr class="{{ $data->status == 'non-aktif' ? 'table-secondary bg-opacity-25' : '' }}">
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            
                            {{-- Info Produk --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        @if($data->gambar)
                                           <img src="{{ asset('img/produk/'.$data->gambar) }}" 
                                                alt="Foto detail produk {{ $data->nama_produk }}" 
                                                class="img-produk-besar">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center text-muted img-produk-besar" style="border: 2px dashed #ccc;">
                                                <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-nama-produk text-uppercase" style="font-family:'Oswald', sans-serif;">{{ $data->nama_produk }}</div>
                                        <div class="text-muted" style="font-size: 13px;">Kode: <strong class="font-monospace" style="color: #E4002B;">{{ $data->kode_produk ?? $data->id }}</strong></div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Kategori --}}
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 shadow-sm" style="font-size: 13px; font-weight: 500;">
                                    {{ optional($data->kategori)->nama_kategori ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            
                            {{-- Harga --}}
                            <td class="fw-bold" style="color: #E4002B; font-size: 16px;">
                                Rp {{ number_format($data->harga, 0, ',', '.') }}
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                @if($data->status == 'non-aktif')
                                    <span class="badge bg-secondary border shadow-sm px-2 py-1" style="font-size: 12px; letter-spacing: 0.5px;">NON-AKTIF</span>
                                @else
                                    <span class="badge bg-success border shadow-sm px-2 py-1" style="font-size: 12px; letter-spacing: 0.5px;">AKTIF</span>
                                @endif
                            </td>
                            
                            {{-- Stok --}}
                            <td class="text-center">
                                @if($data->stock <= 5)
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 shadow-sm">{{ $data->stock }}</span>
                                @else
                                    <strong style="font-size: 16px; color: #202124;">{{ $data->stock }}</strong>
                                @endif
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-outline-primary btn-aksi-jelas" data-bs-toggle="modal" data-bs-target="#modalEditProduk{{ $data->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-aksi-jelas" data-bs-toggle="modal" data-bs-target="#modalHapusProduk{{ $data->id }}">
                                        <i class="bi bi-trash"></i> Hapus
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
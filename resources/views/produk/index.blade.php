@extends('layouts.master')
@section('title', 'Menu Kami')

@section('content')

<div class="container-fluid mt-2">
    
    {{-- 1. HEADER HALAMAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 text-uppercase" style="color: #202124; font-family: 'Oswald', sans-serif;">Daftar Produk</h2>
            <p class="text-muted small">Kelola menu makanan dan minuman warung Anda.</p>
        </div>
        
        <div>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTambahProduk" style="background-color: #E4002B; border: none;"> 
                <i class="bi bi-plus-lg me-1"></i> Tambah Menu
            </button>
        </div>
    </div>



    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-left: 5px solid #E4002B;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 3. TOOLBAR --}}
    <div class="card mb-4 p-3 d-flex flex-row gap-2 bg-white border-0 shadow-sm" style="border-radius: 12px;">
        <a href="{{ url('/home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('produk.excel') }}" class="btn btn-success btn-sm rounded-pill px-3" style="background-color: #198754; border:none;">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="{{ route('produk.pdf') }}" class="btn btn-danger btn-sm rounded-pill px-3" style="background-color: #dc3545; border:none;">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            @if(Route::has('produk.chart'))
                <a href="{{ route('produk.chart') }}" class="btn btn-dark btn-sm rounded-pill px-3">
                    <i class="bi bi-graph-up"></i> Chart
                </a> 
            @endif
        </div>
    </div>

    {{-- 4. TABEL DATA --}}
    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="tabel-produk" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th class="text-center py-3" style="width: 5%;">No.</th>
                        <th class="py-3">Info Produk</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Harga</th>
                        {{-- KOLOM BARU: STATUS --}}
                        <th class="text-center py-3">Status</th> 
                        <th class="text-center py-3">Stok</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataProduk as $data)
                    {{-- Beri warna latar abu-abu tipis jika produk Non-Aktif --}}
                    <tr class="{{ $data->status == 'non-aktif' ? 'table-secondary bg-opacity-25' : '' }}">
                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                        
                        {{-- Info Produk --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3 position-relative">
                                    @if($data->gambar)
                                        <img src="{{ asset('img/produk/'.$data->gambar) }}" class="rounded-3 shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 60px; height: 60px; border:1px dashed #ccc;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-uppercase text-dark" style="font-family:'Oswald'">{{ $data->nama_produk }}</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">ID: <span class="font-monospace">{{ $data->id }}</span></small>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Kategori --}}
                        <td>
                            <span class="badge rounded-pill bg-light text-dark border fw-normal px-3 py-2">
                                {{ optional($data->kategori)->nama_kategori ?? 'Tanpa Kategori' }}
                            </span>
                        </td>
                        
                        {{-- Harga --}}
                        <td class="fw-bold" style="color: #E4002B; font-family:'Oswald'; font-size: 1.1rem;">
                            Rp {{ number_format($data->harga, 0, ',', '.') }}
                        </td>

                        {{-- STATUS (PENTING) --}}
                        <td class="text-center">
                            @if($data->status == 'non-aktif')
                                <span class="badge bg-secondary border shadow-sm">Non-Aktif</span>
                            @else
                                <span class="badge bg-success border shadow-sm">Aktif</span>
                            @endif
                        </td>
                        
                        {{-- Stok --}}
                        <td class="text-center">
                            @if($data->stock <= 5)
                                <span class="badge bg-danger text-white rounded-pill">{{ $data->stock }}</span>
                            @else
                                <span class="fw-bold text-dark">{{ $data->stock }}</span>
                            @endif
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="text-center">
                            <div class="btn-group shadow-sm" role="group">
                                <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#modalEditProduk{{ $data->id }}" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button class="btn btn-sm btn-light border text-danger" data-bs-toggle="modal" data-bs-target="#modalHapusProduk{{ $data->id }}" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
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

{{-- MODALS INCLUDE --}}
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
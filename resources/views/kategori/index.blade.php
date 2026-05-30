@extends('layouts.master')
@section('title', 'Kelola Kategori')

@section('content')

{{-- STYLE KHUSUS TEMA SMOLIE --}}
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
        vertical-align: middle;
    }

    .text-kategori-bold {
        font-size: 16px;
        font-weight: 700;
        color: #202124;
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

    .card-utama {
        border: none;
        border-radius: 12px; /* Disesuaikan dengan halaman produk */
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); /* Shadow lebih halus */
        overflow: hidden;
    }
</style>

<div class="container-fluid mt-2 px-lg-4">
    
    {{-- 1. HEADER SECTION & TOMBOL --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-uppercase fw-bold" style="color: #202124; font-family: 'Oswald', sans-serif;">Daftar Kategori</h2>
            <p class="text-muted fs-6 mb-0">Kelola pengelompokan menu souvenir di Smolie Gift.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            {{-- Tombol Kembali --}}
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold d-flex align-items-center">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>

            {{-- Tombol Tambah --}}
            <button type="button" class="btn btn-primary shadow-sm px-3 fw-bold d-flex align-items-center" 
                    data-bs-toggle="modal" data-bs-target="#modalTambahKategori" 
                    style="background-color: #E4002B; border: none; border-radius: 20px;">
                <i class="bi bi-plus-lg me-1"></i> Tambah Kategori Baru
            </button>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm fs-6" role="alert" style="border-left: 4px solid #198754;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm fs-6" role="alert" style="border-left: 4px solid #E4002B;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. TABEL KATEGORI --}}
    <div class="card card-utama">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0 table-ramah-lansia" id="tabel-kategori" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No.</th>
                            <th style="width: 15%;">ID Sistem</th>
                            <th style="width: 50%;">Nama Kategori</th>
                            <th class="text-center" style="width: 30%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataKategori as $data)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 shadow-sm font-monospace" style="font-size: 13px;">
                                    ID: {{ $data->id }}
                                </span>
                            </td>
                            <td class="text-kategori-bold text-uppercase" style="font-family:'Oswald', sans-serif;">{{ $data->nama_kategori }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Tombol Edit --}}
                                    <button class="btn btn-outline-primary btn-aksi-jelas"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditKategori{{ $data->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button class="btn btn-outline-danger btn-aksi-jelas" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapusKategori{{ $data->id }}">
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

{{-- MODAL SECTION --}}
@foreach ($dataKategori as $data)
    @include('kategori.edit', ['data' => $data])
    @include('kategori.delete', ['data' => $data])
@endforeach
@include('kategori.create')

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Hancurkan DataTable jika sudah ada (mencegah error ganda saat reload)
        if ($.fn.DataTable.isDataTable('#tabel-kategori')) {
            $('#tabel-kategori').DataTable().destroy();
        }

        // Inisialisasi Ulang DataTable
        $('#tabel-kategori').DataTable({
            "responsive": true, 
            "autoWidth": false,
            "stateSave": true, // Menyimpan state (halaman/pencarian terakhir)
            "language": {
                "search": "Cari Kategori:",
                "lengthMenu": "Tampilkan _MENU_ kategori",
                "zeroRecords": "Kategori tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": { "next": ">", "previous": "<" }
            }
        });

        // Fix Layar Modal
        $('.modal').appendTo("body"); 
    });
</script>
@endpush
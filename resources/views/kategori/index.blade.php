@extends('layouts.master')
@section('title', 'Kelola Kategori')

@section('content')

{{-- STYLE KHUSUS RAMAH LANSIA & TEMA SMOLIE --}}
<style>
    .table-ramah-lansia th {
        font-size: 18px;
        background-color: #ffeaea; /* Merah sangat muda agar tidak silau */
        color: #b8001f;
        padding: 20px 15px;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        border-bottom: 3px solid #f1f3f5;
    }

    .table-ramah-lansia td {
        padding: 20px 15px;
        font-size: 18px;
        color: #333;
        font-family: 'Poppins', sans-serif;
    }

    .text-kategori-bold {
        font-size: 20px;
        font-weight: 700;
        color: #202124;
    }

    .btn-aksi-jelas {
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: 0.2s;
        border-width: 2px;
    }
    
    /* Efek hanya bergerak naik tanpa ganti warna */
.btn-aksi-jelas:hover {
    transform: translateY(-5px) !important; /* Gerak naik */
    background-color: transparent !important; /* Tetap transparan */
    color: inherit !important; /* Warna teks tetap */
    box-shadow: 0 5px 15px rgba(0,0,0,0.1); /* Bayangan halus agar gerak terasa */
}

    .card-utama {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        overflow: hidden;
    }
</style>

<div class="container-fluid py-4 px-lg-4">
    
    {{-- 1. HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="fw-bold mb-1" style="color: #202124; font-family: 'Poppins', sans-serif;">
                <i class="bi bi-grid-fill text-danger me-2"></i>Daftar Kategori
            </h1>
            <p class="text-muted fs-5 mb-0">Kelola pengelompokan menu makanan dan minuman Smolie Gift.</p>
        </div>

        <div class="d-flex gap-3">
            {{-- Tombol Kembali --}}
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary px-4 py-3 fw-bold fs-5 shadow-sm" style="border-radius: 15px; border-width: 2px;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            {{-- Tombol Tambah --}}
            <button type="button" class="btn btn-danger px-4 py-3 fw-bold fs-5 shadow" 
                    data-bs-toggle="modal" data-bs-target="#modalTambahKategori" 
                    style="background-color: #E4002B; border: none; border-radius: 15px;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kategori Baru
            </button>
        </div>
    </div>

    {{-- 2. TABEL KATEGORI --}}
    <div class="card card-utama">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-ramah-lansia" id="tabel-kategori">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:10%">No.</th>
                            <th style="width:15%">ID Sistem</th>
                            <th>Nama Kategori Menu</th>
                            <th class="text-center" style="width:30%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataKategori as $data)
                        <tr>
                            <td class="text-center fw-bold text-muted fs-4">{{ $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 fs-6 fw-normal" style="border-radius: 8px;">
                                    #{{ $data->id }}
                                </span>
                            </td>
                            <td class="text-kategori-bold text-uppercase">{{ $data->nama_kategori }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-3">
                                    {{-- Tombol Ubah --}}
                                    <button class="btn btn-outline-warning btn-aksi-jelas text-dark"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditKategori{{ $data->id }}">
                                        <i class="bi bi-pencil-square fs-4"></i> Ubah Nama
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button class="btn btn-outline-danger btn-aksi-jelas" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHapusKategori{{ $data->id }}">
                                        <i class="bi bi-trash fs-4"></i> Hapus
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

{{-- MODAL SECTION (LOGIKA TETAP AMAN) --}}
@foreach ($dataKategori as $data)
    @include('kategori.edit', ['data' => $data])
    @include('kategori.delete', ['data' => $data])
@endforeach

@include('kategori.create')

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Inisialisasi DataTable Ramah Lansia
        $('#tabel-kategori').DataTable({
            "responsive": true, 
            "autoWidth": false,
            "language": {
                "search": "Cari Kategori:",
                "searchPlaceholder": "Ketik nama kategori...",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ kategori",
                "paginate": {
                    "next": "<i class='bi bi-chevron-right'></i>",
                    "previous": "<i class='bi bi-chevron-left'></i>"
                }
            },
            "dom": '<"d-flex justify-content-between align-items-center p-4 bg-light"f>t<"d-flex justify-content-between align-items-center p-4"ip>',
        });

        // Fix Layar Abu-Abu
        $('.modal').appendTo("body"); 
    });
</script>
@endpush
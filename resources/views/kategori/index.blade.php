@extends('layouts.master')
@section('title', 'Kategori Menu')

@section('content')

{{-- STYLE KHUSUS TEMA KFC --}}
<style>
    :root {
        --kfc-red: #E4002B;
        --kfc-dark: #202124;
        --gray-bg: #f8f9fa;
    }
    body { background-color: var(--gray-bg); }

    /* Card Style */
    .card-kfc {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
        background: white;
    }

    /* Typography */
    .page-title {
        font-weight: 800;
        color: var(--kfc-dark);
        letter-spacing: -0.5px;
    }

    /* Buttons */
    .btn-kfc-primary {
        background-color: var(--kfc-red);
        color: white;
        border: none;
        border-radius: 50px; /* Pill shape */
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s;
    }
    .btn-kfc-primary:hover {
        background-color: #c00024;
        color: white;
        transform: translateY(-2px);
    }

    .btn-kfc-outline {
        border: 2px solid #ddd;
        color: #666;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-kfc-outline:hover {
        border-color: var(--kfc-dark);
        color: var(--kfc-dark);
    }

    /* Table Styling */
    .table-kfc thead th {
        background-color: white;
        color: #999;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 2px solid #eee;
        padding: 1.2rem 1rem;
    }
    .table-kfc tbody td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f9f9f9;
        font-size: 0.95rem;
    }
    
    /* Action Buttons in Table */
    .btn-action {
        border-radius: 8px;
        font-size: 0.8rem;
        padding: 6px 12px;
        font-weight: 600;
    }
</style>

<div class="container py-5">
    
    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h2 class="page-title mb-1"><i class="bi bi-grid-fill text-danger me-2"></i>Tabel Kategori</h2>
            <p class="text-muted mb-0">Kelola kategori menu makanan dan minuman.</p>
        </div>

        <div class="d-flex gap-2">
            {{-- Tombol Kembali --}}
            <a href="{{ url('/home') }}" class="btn btn-kfc-outline">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            {{-- Tombol Tambah --}}
            <button type="button" class="btn btn-kfc-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="bi bi-plus-lg"></i> Tambah Kategori
            </button>
        </div>
    </div>

    {{-- CARD TABLE --}}
    <div class="card card-kfc">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-kfc table-hover mb-0" id="tabel-kategori">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:5%">No.</th>
                            <th style="width:10%">ID</th>
                            <th>Nama Kategori</th>
                            <th style="width:20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataKategori as $data)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $data->id }}</span></td>
                            <td class="fw-bold text-dark">{{ $data->nama_kategori }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Ubah --}}
                                    <button class="btn btn-warning btn-action text-white"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditKategori{{ $data->id }}">
                                        <i class="bi bi-pencil-square"></i> Ubah
                                    </button>

                                    {{-- Tombol Hapus --}}
                                    <button class="btn btn-danger btn-action" 
                                        style="background-color: var(--kfc-red); border:none;"
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

{{-- LOGIKA MODAL (TIDAK DIUBAH) --}}
@foreach ($dataKategori as $data)
    @include('kategori.edit', ['data' => $data])
    @include('kategori.delete', ['data' => $data])
@endforeach

@include('kategori.create')

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // 1. Inisialisasi DataTable dengan styling yang lebih bersih
        $('#tabel-kategori').DataTable({
            "responsive": true, 
            "autoWidth": false,
            "language": {
                "search": "Cari Kategori:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "<i class='bi bi-chevron-right'></i>",
                    "previous": "<i class='bi bi-chevron-left'></i>"
                }
            },
            // Menghilangkan styling default search box agar lebih modern
            "dom": '<"d-flex justify-content-between align-items-center p-4"f>t<"d-flex justify-content-between align-items-center p-4"ip>',
        });

        // 2. SOLUSI FIX LAYAR ABU-ABU (Magic Script) - TETAP ADA
        $('.modal').appendTo("body"); 
    });
</script>
@endpush
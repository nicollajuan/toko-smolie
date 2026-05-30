@extends('layouts.master')
@section('title', 'Kelola Staf - Smolie Gift')

@section('content')

{{-- STYLE KONSISTEN DENGAN TEMA SMOLIE GIFT --}}
<style>
    :root {
        --smolie-red: #DD3827;
        --text-dark: #202124;
    }
    
    .table-ramah-lansia th {
        font-size: 14px;
        background-color: #f8f9fa; 
        color: #495057;
        padding: 15px; 
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
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }

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
    
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--smolie-red);
        box-shadow: 0 0 0 0.2rem rgba(221, 56, 39, 0.15);
    }
</style>

<div class="container-fluid mt-2 px-lg-4">
    
    {{-- 1. HEADER HALAMAN & TOMBOL TAMBAH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="mb-1 text-uppercase fw-bold" style="color: var(--text-dark); font-family: 'Oswald', sans-serif;">
                <i class="bi bi-people-fill me-2" style="color: var(--smolie-red);"></i>Kelola Staf
            </h2>
            <p class="text-muted fs-6 mb-0">Manajemen akun Admin dan Kasir Smolie Gift.</p>
        </div>
        
        <div>
            <button type="button" class="btn text-white rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalTambahStaf" style="background-color: var(--smolie-red);">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah Staf
            </button>
        </div>
    </div>

    {{-- 2. NOTIFIKASI --}}
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
    @if($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3 border-0" style="background-color: #FFEBEE; color: #C62828;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- 3. TABEL DATA STAF --}}
    <div class="card card-utama">
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0 table-ramah-lansia" id="tabel-staf" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%">No.</th>
                            <th style="width: 25%">Nama Lengkap</th>
                            <th style="width: 30%">Email Akses</th>
                            <th class="text-center" style="width: 20%">Peran (Role)</th>
                            <th class="text-center" style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataStaf as $staf)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                            
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 15px;">{{ $staf->name }}</div>
                                @if(Auth::id() == $staf->id)
                                    <span class="badge bg-light text-success border border-success mt-1" style="font-size: 10px;">Anda Sendiri</span>
                                @endif
                            </td>
                            
                            <td class="text-muted">{{ $staf->email }}</td>
                            
                            <td class="text-center">
                                @if($staf->usertype == 'admin')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-calculator-fill me-1"></i> Kasir</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Edit --}}
                                    <button type="button" class="btn btn-warning btn-aksi-bulat text-dark" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $staf->id }}" title="Edit Staf">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    {{-- Tombol Hapus (Cegah hapus diri sendiri) --}}
                                    @if(Auth::id() != $staf->id)
                                        <button type="button" class="btn btn-danger btn-aksi-bulat text-white" data-bs-toggle="modal" data-bs-target="#modalHapus{{ $staf->id }}" title="Hapus Staf">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-aksi-bulat text-white" disabled title="Tidak bisa hapus diri sendiri">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- ======================================================= --}}
                        {{-- MODAL EDIT STAF (Unik per ID) --}}
                        {{-- ======================================================= --}}
                        <div class="modal fade" id="modalEdit{{ $staf->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                    <div class="modal-header bg-light border-0 px-4 pt-4 pb-3">
                                        <h5 class="modal-title fw-bold">Edit Data Staf</h5>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.staf.update', $staf->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                                                <input type="text" name="name" class="form-control" value="{{ $staf->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">EMAIL AKSES</label>
                                                <input type="email" name="email" class="form-control" value="{{ $staf->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted">PERAN (ROLE)</label>
                                                <select name="usertype" class="form-select" required>
                                                    <option value="admin" {{ $staf->usertype == 'admin' ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                                                    <option value="kasir" {{ $staf->usertype == 'kasir' ? 'selected' : '' }}>Kasir (Akses Transaksi)</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold small text-muted">PASSWORD BARU (Opsional)</label>
                                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-0 px-4 pb-4 pt-3">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- ======================================================= --}}
                        {{-- MODAL HAPUS STAF (Unik per ID) --}}
                        {{-- ======================================================= --}}
                        @if(Auth::id() != $staf->id)
                        <div class="modal fade" id="modalHapus{{ $staf->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    <div class="modal-body p-4 text-center">
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light text-danger rounded-circle" style="width: 70px; height: 70px;">
                                                <i class="bi bi-exclamation-triangle fs-1"></i>
                                            </div>
                                        </div>
                                        <h5 class="fw-bold mb-2 text-dark">Hapus Akun?</h5>
                                        <p class="text-muted mb-4 small">Akun <strong>{{ $staf->name }}</strong> tidak akan bisa login lagi. Data transaksi yang pernah ia buat akan tetap tersimpan.</p>
                                        
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                                            <form action="{{ route('admin.staf.delete', $staf->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger fw-bold px-4 shadow-sm" style="border-radius: 12px;">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ======================================================= --}}
{{-- MODAL TAMBAH STAF BARU --}}
{{-- ======================================================= --}}
<div class="modal fade" id="modalTambahStaf" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-light border-0 px-4 pt-4 pb-3">
                <h5 class="modal-title fw-bold">Tambah Staf Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.staf.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NAMA LENGKAP <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">EMAIL AKSES <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@smoliegift.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PERAN (ROLE) <span class="text-danger">*</span></label>
                        <select name="usertype" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Peran --</option>
                            <option value="kasir">Kasir (Akses Transaksi)</option>
                            <option value="admin">Admin (Akses Penuh)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">PASSWORD AWAL <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 pb-4 pt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white rounded-pill fw-bold px-4 shadow-sm" style="background-color: var(--smolie-red);">Simpan Staf</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabel-staf')) {
            $('#tabel-staf').DataTable().destroy();
        }
        $('#tabel-staf').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "search": "Cari Staf:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Tidak ada data staf ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "paginate": { "next": ">", "previous": "<" }
            }
        });
        // Pindahkan modal ke body agar tidak tertutup z-index
        $('.modal').appendTo("body");
    });
</script>
@endpush
@endsection
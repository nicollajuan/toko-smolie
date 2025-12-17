@extends('layouts.master')

@section('title', 'Edit Profil Admin')

@section('content')
<div class="container-fluid mt-4">

    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-uppercase mb-0" style="font-family: 'Oswald', sans-serif; color: #202124;">
            Edit Profil
        </h2>
    </div>

    <div class="row">
        
        {{-- KOLOM KIRI: PREVIEW FOTO PROFIL --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100" style="border-radius: 16px;">
                <div class="mb-3 mx-auto position-relative" style="width: 150px; height: 150px;">
                    
                    {{-- Cek Foto --}}
                    @if($user->foto && file_exists(public_path('img/user/'.$user->foto)))
                        <img src="{{ asset('img/user/' . $user->foto) }}" 
                             alt="Foto Profil" 
                             class="rounded-circle img-thumbnail shadow-sm object-fit-cover w-100 h-100 border-danger">
                    @else
                        <img src="{{ asset('template/img/star.jpeg') }}" 
                             alt="Foto Default" 
                             class="rounded-circle img-thumbnail shadow-sm object-fit-cover w-100 h-100 border-danger">
                    @endif

                </div>
                <h5 class="fw-bold mb-1" style="font-family: 'Oswald'">{{ $user->name }}</h5>
                <p class="text-muted mb-3 badge bg-light text-dark border">{{ ucfirst($user->usertype) }}</p>
                <small class="text-muted d-block fst-italic">Gunakan formulir di samping untuk mengubah data.</small>
            </div>
        </div>

        {{-- KOLOM KANAN: FORMULIR EDIT --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white py-3 border-0" style="border-bottom: 2px solid #f8f9fa !important;">
                     <h5 class="mb-0 fw-bold text-danger text-uppercase" style="font-family: 'Oswald'">Informasi Akun</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- TAMPILKAN ERROR UMUM JIKA ADA --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM UPDATE --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- 1. UPLOAD FOTO --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Ganti Foto Profil</label>
                            {{-- Tambahkan class is-invalid jika ada error --}}
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maks: 2MB.</small>
                            
                            {{-- Pesan Error Foto --}}
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- 2. NAMA & EMAIL --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-danger"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-danger"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        {{-- 3. GANTI PASSWORD --}}
                        <h5 class="mb-3 fw-bold text-danger text-uppercase" style="font-family: 'Oswald'">Keamanan</h5>
                        <div class="alert alert-light border small mb-3">
                            <i class="bi bi-info-circle-fill me-1"></i> Kosongkan jika tidak ingin mengubah password.
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-danger"></i></span>
                                    <input type="password" 
                                        name="password" 
                                        id="password" 
                                        class="form-control border-start-0 ps-0 border-end-0 @error('password') is-invalid @enderror"
                                        autocomplete="new-password">
                                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassword('password', 'icon-pass')">
                                        <i class="bi bi-eye" id="icon-pass"></i>
                                    </button>
                                    
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Ulangi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-danger"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 ps-0 border-end-0">
                                    <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" onclick="togglePassword('password_confirmation', 'icon-confirm')">
                                        <i class="bi bi-eye" id="icon-confirm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('profile') }}" class="btn btn-light border fw-bold text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary fw-bold px-4" style="background-color: #E4002B; border: none; border-radius: 50px;">
                                <i class="bi bi-save2-fill me-2"></i> SIMPAN PERUBAHAN
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT TOGGLE PASSWORD --}}
<script>
    function togglePassword(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash"); 
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye"); 
        }
    }
</script>
@endsection
@extends('layouts.master')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-11">
            
            {{-- Card sudah otomatis membulat dan bershadow dari CSS master.blade.php --}}
            <div class="card border-0 overflow-hidden">
                
                {{-- Header Card: Ganti warna ke var(--theme-primary) dan font ke theme-font --}}
                <div class="card-header text-white py-3 border-0" style="background-color: var(--theme-primary);">
                    <h5 class="mb-0 theme-font" style="letter-spacing: 0.5px;">
                        <i class="bi bi-person-circle me-2"></i> PROFIL SAYA
                    </h5>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-start">
                        
                        {{-- KOLOM KIRI: FOTO --}}
                        <div class="col-md-4 text-center mb-4 mb-md-0 border-end border-light">
                            <div class="position-relative d-inline-block mb-3">
                                {{-- Logika Foto Profil --}}
                                @if(Auth::user()->foto && file_exists(public_path('img/user/'.Auth::user()->foto)))
                                    <img src="{{ asset('img/user/'.Auth::user()->foto) }}" 
                                         class="rounded-circle img-thumbnail shadow-sm" 
                                         alt="Foto Profil" 
                                         style="width: 180px; height: 180px; object-fit: cover; border: 5px solid var(--theme-secondary);">
                                @else
                                    {{-- Placeholder Default --}}
                                    <img src="{{ asset('template/img/star.jpeg') }}" 
                                         class="rounded-circle img-thumbnail shadow-sm" 
                                         alt="Foto Default" 
                                         style="width: 180px; height: 180px; object-fit: cover; border: 5px solid var(--theme-secondary);">
                                @endif
                                
                                <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-white rounded-circle" title="Online" style="border-width: 3px !important;"></span>
                            </div>

                            {{-- Nama & Badge Role --}}
                            <h4 class="theme-font text-dark mb-2">{{ $user->name }}</h4>
                            <span class="badge rounded-pill text-dark px-3 py-2 fw-bold mb-3" style="background-color: var(--theme-secondary);">
                                {{ ucfirst($user->usertype) }}
                            </span>
                        </div>

                        {{-- KOLOM KANAN: DETAIL --}}
                        <div class="col-md-8 ps-md-5">
                            {{-- Judul Seksi --}}
                            <h5 class="theme-font mb-4 border-bottom pb-3" style="color: var(--theme-primary);">Informasi Akun</h5>
                            
                            <table class="table table-borderless mb-4">
                                <tbody>
                                    <tr>
                                        <td class="text-muted" width="35%"><i class="bi bi-person-vcard me-2"></i> Nama Lengkap</td>
                                        <td width="5%">:</td>
                                        <td class="fw-bold text-dark">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><i class="bi bi-envelope-at me-2"></i> Email Address</td>
                                        <td>:</td>
                                        <td class="fw-bold text-dark">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><i class="bi bi-shield-lock me-2"></i> Role Akses</td>
                                        <td>:</td>
                                        <td>
                                            @if($user->usertype == 'admin')
                                                <span class="fw-bold" style="color: var(--theme-primary);"><i class="bi bi-star-fill text-warning"></i> Administrator</span>
                                            @else
                                                <span class="text-primary fw-bold">Kasir / Staff</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><i class="bi bi-calendar-event me-2"></i> Bergabung Sejak</td>
                                        <td>:</td>
                                        <td class="fw-bold text-dark">{{ $user->created_at->format('d F Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Area Tombol --}}
                            <div class="d-flex gap-2 mt-4 pt-4 border-top">
                                <a href="{{ url('/home') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                {{-- Tombol Edit menggunakan btn-primary agar warnanya senada dengan tema --}}
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
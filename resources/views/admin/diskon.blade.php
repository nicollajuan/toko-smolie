@extends('layouts.master')
@section('title', 'Pengaturan Diskon Member')

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-uppercase" style="font-family: 'Oswald', sans-serif; color: #202124;">
                <i class="bi bi-percent me-2 text-danger"></i> Pengaturan Diskon Member
            </h2>
            <p class="text-muted mb-0 small">Atur diskon per level loyalitas pelanggan.</p>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- PENJELASAN --}}
    <div class="alert border-0 shadow-sm rounded-4 mb-4" style="background:#FFF8F0; border-left: 4px solid #FFA500 !important;">
        <div class="fw-bold mb-1"><i class="bi bi-lightbulb-fill text-warning me-2"></i> Cara Kerja Diskon</div>
        <ul class="mb-0 small text-muted ps-3">
            <li><strong>Diskon per Level</strong> adalah diskon tetap untuk setiap level member (Bronze, Silver, Gold, Platinum).</li>
            <li><strong>Diskon Manual</strong> adalah override sementara — aktifkan untuk memberi diskon khusus, misal saat promo atau event.</li>
            <li>Jika Diskon Manual diaktifkan, nilainya akan <strong>menggantikan</strong> diskon per level.</li>
        </ul>
    </div>

    <form action="{{ route('admin.diskon.update') }}" method="POST">
        @csrf

        <div class="row g-4">
            @php
                $tierColors = [
                    'Bronze'   => ['bg' => '#CD7F32', 'light' => '#FFF4EC', 'border' => '#F0C9A0'],
                    'Silver'   => ['bg' => '#8E9EAB', 'light' => '#F0F4F8', 'border' => '#C5D0DA'],
                    'Gold'     => ['bg' => '#D4A017', 'light' => '#FFFBEC', 'border' => '#F5E08A'],
                    'Platinum' => ['bg' => '#2C2C2C', 'light' => '#F5F5F5', 'border' => '#CCCCCC'],
                ];
                $tierIcons = [
                    'Bronze'   => 'bi-award',
                    'Silver'   => 'bi-award-fill',
                    'Gold'     => 'bi-trophy',
                    'Platinum' => 'bi-trophy-fill',
                ];
            @endphp

            @foreach($diskon as $item)
            @php
                $colors = $tierColors[$item->level_member] ?? ['bg'=>'#888','light'=>'#f9f9f9','border'=>'#ddd'];
                $icon   = $tierIcons[$item->level_member] ?? 'bi-star';
            @endphp
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 18px; border: 1.5px solid {{ $colors['border'] }} !important;">

                    {{-- HEADER LEVEL --}}
                    <div class="card-header border-0 d-flex align-items-center gap-3 py-3 px-4" 
                         style="background: {{ $colors['light'] }}; border-radius: 18px 18px 0 0;">
                        <div style="width:46px;height:46px;border-radius:12px;background:{{ $colors['bg'] }};display:flex;align-items:center;justify-content:center;">
                            <i class="bi {{ $icon }} text-white fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:1.05rem; color: #2D3142;">{{ $item->level_member }}</div>
                            <div class="small text-muted">Level Member</div>
                        </div>
                    </div>

                    <div class="card-body px-4 pt-3 pb-4">

                        {{-- DISKON PER LEVEL --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">
                                <i class="bi bi-percent me-1"></i> Diskon Per Level
                            </label>
                            <div class="input-group">
                                <input 
                                    type="number" 
                                    name="diskon[{{ $item->id }}][persentase]"
                                    class="form-control @error('diskon.'.$item->id.'.persentase') is-invalid @enderror"
                                    value="{{ old('diskon.'.$item->id.'.persentase', $item->persentase_diskon) }}"
                                    min="0" max="100" step="0.5"
                                    style="border-radius: 10px 0 0 10px;"
                                >
                                <span class="input-group-text fw-bold" style="border-radius: 0 10px 10px 0;">%</span>
                            </div>
                            <small class="text-muted">Diterapkan otomatis ke semua member {{ $item->level_member }}.</small>
                        </div>

                        <hr class="my-3 opacity-25">

                        {{-- DISKON MANUAL --}}
                        <div class="mb-3">
                            <div class="form-check form-switch mb-2">
                                <input 
                                    class="form-check-input diskon-manual-toggle" 
                                    type="checkbox" 
                                    name="diskon[{{ $item->id }}][manual_aktif]"
                                    id="manual_{{ $item->id }}"
                                    value="1"
                                    data-target="manual_input_{{ $item->id }}"
                                    {{ $item->diskon_manual_aktif ? 'checked' : '' }}
                                    style="cursor:pointer;"
                                >
                                <label class="form-check-label fw-bold small text-uppercase text-muted" for="manual_{{ $item->id }}">
                                    Aktifkan Diskon Manual
                                </label>
                            </div>

                            <div id="manual_input_{{ $item->id }}" style="{{ $item->diskon_manual_aktif ? '' : 'opacity:0.4; pointer-events:none;' }}">
                                <div class="input-group">
                                    <input 
                                        type="number" 
                                        name="diskon[{{ $item->id }}][nominal_manual]"
                                        class="form-control"
                                        value="{{ old('diskon.'.$item->id.'.nominal_manual', $item->nominal_diskon_manual) }}"
                                        min="0" max="100" step="0.5"
                                        style="border-radius: 10px 0 0 10px;"
                                    >
                                    <span class="input-group-text fw-bold" style="border-radius: 0 10px 10px 0;">%</span>
                                </div>
                                <small class="text-muted">Menggantikan diskon per level saat aktif.</small>
                            </div>
                        </div>

                        {{-- PREVIEW DISKON AKTIF --}}
                        <div class="rounded-3 p-3 text-center" style="background: {{ $colors['light'] }}; border: 1px dashed {{ $colors['border'] }};">
                            <div class="small text-muted mb-1">Diskon Aktif Saat Ini</div>
                            <div class="fw-bold" style="font-size: 1.6rem; color: {{ $colors['bg'] }};">
                                {{ $item->getDiskonAktif() }}%
                            </div>
                            @if($item->diskon_manual_aktif)
                                <span class="badge" style="background:{{ $colors['bg'] }}; color:white; font-size:0.7rem;">Manual Aktif</span>
                            @else
                                <span class="badge bg-light text-muted border" style="font-size:0.7rem;">Per Level</span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-danger fw-bold px-5 shadow-sm" style="border-radius: 50px; background-color: #DD3827; border: none;">
                <i class="bi bi-save2-fill me-2"></i> Simpan Pengaturan Diskon
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.diskon-manual-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const targetId = this.dataset.target;
            const target = document.getElementById(targetId);
            if (this.checked) {
                target.style.opacity = '1';
                target.style.pointerEvents = 'auto';
            } else {
                target.style.opacity = '0.4';
                target.style.pointerEvents = 'none';
            }
        });
    });
</script>
@endsection

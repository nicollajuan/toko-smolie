@extends('layouts.master')
@section('title', 'Ulasan Pelanggan')

@section('content')
<div class="container-fluid p-4">
    
    <h3 class="fw-bold text-uppercase mb-4" style="font-family: 'Oswald', sans-serif; color: #E4002B;">
        <i class="bi bi-chat-quote-fill me-2"></i> Ulasan & Rating Pelanggan
    </h3>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 text-secondary small text-uppercase">Tanggal</th>
                            <th class="py-3 text-secondary small text-uppercase">Pelanggan</th>
                            <th class="py-3 text-secondary small text-uppercase">Rating</th>
                            <th class="py-3 text-secondary small text-uppercase">Komentar</th>
                            <th class="py-3 text-secondary small text-uppercase">Menu Dipesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="text-muted" style="font-size: 0.9rem;">
                                {{ date('d M Y, H:i', strtotime($review->created_at)) }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center me-2 fw-bold" style="width: 35px; height: 35px;">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold">{{ $review->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                {{-- Logika Tampilan Bintang --}}
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star text-muted opacity-25"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1 text-dark fw-bold small">({{ $review->rating }})</span>
                                </div>
                            </td>
                            <td>
                                @if($review->komentar)
                                    <div class="bg-light p-2 rounded text-muted fst-italic small">
                                        "{{Str::limit($review->komentar, 100)}}"
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border">- Tidak ada komentar -</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('transaksi.struk', $review->transaksi_id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-receipt me-1"></i> Lihat Order
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-square-text display-4 mb-3 d-block opacity-25"></i>
                                Belum ada ulasan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
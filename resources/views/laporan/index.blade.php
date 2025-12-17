@extends('layouts.master')
@section('title', 'Laporan Penjualan')

@section('content')

{{-- Custom Style untuk KFC Look --}}
<style>
    :root {
        --kfc-red: #E4002B;
        --kfc-black: #202124;
        --kfc-gray: #f8f9fa;
    }
    body {
        background-color: #f4f4f4; /* Background abu-abu muda bersih */
    }
    .text-kfc {
        color: var(--kfc-red) !important;
    }
    .bg-kfc {
        background-color: var(--kfc-red) !important;
        color: white;
    }
    .btn-kfc-outline {
        border: 2px solid var(--kfc-red);
        color: var(--kfc-red);
        font-weight: 700;
        border-radius: 50px; /* Pill shape */
        transition: all 0.3s;
    }
    .btn-kfc-outline:hover {
        background-color: var(--kfc-red);
        color: white;
    }
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .card-header-modern {
        background-color: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 1.5rem;
    }
    .table-modern thead th {
        background-color: var(--kfc-black);
        color: white;
        border: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        padding: 15px;
    }
    .table-modern tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    .badge-pill {
        border-radius: 50px;
        padding: 0.5em 1em;
    }
</style>

<div class="container mt-5">
    
    {{-- Header Section --}}
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h2 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1px; color: #202124;">
                <i class="bi bi-file-earmark-bar-graph text-kfc"></i> Laporan Penjualan
            </h2>
            <p class="text-muted mb-0">Ringkasan transaksi harian dan performa menu.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ url('/home') }}" class="btn btn-kfc-outline px-4">
                <i class="bi bi-arrow-left"></i> KEMBALI KE HOME
            </a>
        </div>
    </div>

    {{-- User Greeting Card --}}
    <div class="card card-modern mb-4 bg-white">
        <div class="card-body d-flex align-items-center p-4">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                <i class="bi bi-person-fill fs-4 text-kfc"></i>
            </div>
            <div>
                <small class="text-muted text-uppercase fw-bold">Login Session</small>
                <h5 class="mb-0">Halo, <strong>{{ Auth::user()->name }}</strong></h5>
            </div>
            <div class="ms-auto d-none d-md-block">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Admin Access</span>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="d-flex gap-2 mb-4">
        <a href="{{ route('laporan.excel') }}" class="btn btn-success rounded-pill shadow-sm px-4 fw-bold">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
        <a href="{{ route('laporan.pdf') }}" class="btn btn-danger rounded-pill shadow-sm px-4 fw-bold" style="background-color: #E4002B; border: none;">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
    </div>

    <div class="row">
        {{-- BAGIAN 2: GRAFIK PENJUALAN --}}
        <div class="col-md-12 mb-5">
            <div class="card card-modern">
                <div class="card-header-modern d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">PRODUK TERLARIS</h5>
                        <small class="text-muted">Statistik item menu yang paling diminati</small>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-4 text-muted"></i>
                </div>
                <div class="card-body p-4">
                    <div id="chartPenjualan"></div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 3: TABEL RIWAYAT TRANSAKSI --}}
        <div class="col-md-12">
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt me-2 text-kfc"></i>RIWAYAT PEMESANAN PRODUK</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern table-hover mb-0" id="tabel-laporan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kode TRX</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Pembayaran</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan as $data)
                                <tr>
                                    <td class="fw-bold text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $data->created_at->format('d M Y') }}</td>
                                    <td class="text-kfc fw-bold" style="font-family: monospace; font-size: 1.1em;">
                                        {{ $data->kode_transaksi }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $data->nama_pembeli }}</span>
                                            <span class="text-muted small"><i class="bi bi-whatsapp"></i> {{ $data->no_hp ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($data->jenis_pesanan == 'delivery')
                                            <span class="badge badge-pill bg-danger mb-1"><i class="bi bi-truck"></i> Delivery</span>
                                            <div class="text-muted small mt-1" style="max-width: 200px; line-height: 1.2;">
                                                {{Str::limit($data->alamat_pengiriman, 30)}}
                                            </div>
                                        @elseif($data->jenis_pesanan == 'dine_in')
                                            <span class="badge badge-pill bg-dark"><i class="bi bi-shop"></i> Dine In</span>
                                        @else
                                            <span class="badge badge-pill bg-warning text-dark"><i class="bi bi-bag"></i> Takeaway</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->metode_pembayaran == 'qris')
                                            <span class="badge border border-dark text-dark bg-white rounded-1"><i class="bi bi-qr-code"></i> QRIS</span>
                                        @else
                                            <span class="badge bg-success rounded-1"><i class="bi bi-cash"></i> Tunai</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold fs-6">Rp {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($data->status == 'selesai')
                                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                        @else
                                            <span class="spinner-border spinner-border-sm text-warning" role="status"></span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{-- Pagination biasanya muncul disini jika ada --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script ApexCharts (Grafik Penjualan) --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Ambil data dari Controller
    var labelProduk = {!! json_encode($dataLabel) !!};
    var dataPenjualan = {!! json_encode($dataPenjualan) !!};

    var options = {
        series: [{
            name: 'Terjual',
            data: dataPenjualan
        }],
        chart: {
            type: 'bar',
            height: 380,
            fontFamily: 'sans-serif',
            toolbar: { show: false }
        },
        // Warna KFC: Merah Utama, Kuning Sekunder, lalu Abu-abu
        colors: ['#E4002B', '#ffc107', '#202124', '#6c757d'],
        plotOptions: {
            bar: {
                borderRadius: 8, // Bar lebih bulat
                columnWidth: '45%',
                distributed: true, 
            }
        },
        dataLabels: { 
            enabled: true,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        legend: { show: false },
        xaxis: {
            categories: labelProduk,
            labels: {
                style: {
                    fontWeight: 600,
                    fontSize: '12px'
                }
            }
        },
        grid: {
            strokeDashArray: 4, // Garis putus-putus modern
            yaxis: {
                lines: { show: true }
            }
        },
        yaxis: {
            title: { text: 'Jumlah Porsi' }
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function (val) { return val + " Items" }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chartPenjualan"), options);
    chart.render();
</script>

{{-- Script DataTable dengan Styling Bootstrap 5 --}}
<script>
    $(document).ready(function () {
        $('#tabel-laporan').DataTable({
            "language": {
                "search": "Cari Transaksi:",
                "paginate": {
                    "next": "<i class='bi bi-chevron-right'></i>",
                    "previous": "<i class='bi bi-chevron-left'></i>"
                }
            },
            "dom": '<"d-flex justify-content-between align-items-center mb-3 p-3"f>t<"p-3"p>',
        });
    });
</script>
@endpush
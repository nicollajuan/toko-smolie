@extends('layouts.master')
@section('title', 'Transaksi Tunai Kasir')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold"><i class="bi bi-cash-stack me-2"></i> Transaksi Tunai Kasir</h2>
            <p class="text-muted mb-0">Gunakan form ini untuk mencatat pesanan custom atau penjualan offline secara cepat.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Transaksi
            </a>
        </div>
    </div>

    <div class="card shadow border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="alert alert-info border-0 shadow-sm mb-4">
                <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                <strong>Catatan Kasir:</strong> Ketik nama produk secara manual jika pembeli memesan produk <em>custom</em>. Jika produk sudah ada di katalog, pilih dari <em>dropdown</em> agar stok terpotong otomatis.
            </div>

            <form action="{{ route('transaksi.kasir.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card bg-light border-0 p-3 h-100">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Data Pembeli</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Pembeli <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_pembeli" value="{{ old('nama_pembeli') }}" placeholder="Masukkan nama pelanggan" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor WhatsApp</label>
                                <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789 (Opsional)">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Data Produk</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Pilih dari Katalog (Opsional)</label>
                                <select id="produkSelect" name="produk_id" class="form-select border-primary">
                                    <option value="">-- Ketik Manual atau Pilih Produk --</option>
                                    @foreach($produk as $item)
                                        <option value="{{ $item->id }}" data-nama="{{ $item->nama_produk }}" data-harga="{{ $item->harga }}">
                                            {{ $item->nama_produk }} - Rp {{ number_format($item->harga, 0, ',', '.') }} (Stok {{ $item->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-7 mb-3">
                                    <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" id="namaProduk" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}" placeholder="Nama barang..." required>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label class="form-label fw-bold">Harga Satuan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="hargaSatuan" name="harga_satuan" class="form-control" value="{{ old('harga_satuan') }}" min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Qty <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah" class="form-control text-center" value="{{ old('jumlah', 1) }}" min="1" required>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-bold">Catatan Pesanan</label>
                                    <input type="text" name="catatan" class="form-control" value="{{ old('catatan') }}" placeholder="Cth: Pita merah, kartu ucapan">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <input type="hidden" name="metode_pembayaran" value="tunai">

                        <div class="alert alert-success d-flex align-items-center mb-4 border-0">
                            <i class="bi bi-check-circle-fill fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Status: Selesai (Tunai)</h6>
                                <small>Transaksi ini akan langsung masuk ke laporan pendapatan.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Transaksi Tunai
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('produkSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const nama = selected.dataset.nama || '';
        const harga = selected.dataset.harga || '';
        
        const inputNama = document.getElementById('namaProduk');
        const inputHarga = document.getElementById('hargaSatuan');

        if (this.value !== "") {
            // Jika pilih dari katalog, isi otomatis dan KUNCI kolomnya agar tidak salah ketik
            inputNama.value = nama;
            inputHarga.value = harga;
            inputNama.readOnly = true;
            inputHarga.readOnly = true;
            inputNama.classList.add('bg-light');
            inputHarga.classList.add('bg-light');
        } else {
            // Jika kembali ke manual, KOSONGKAN dan BUKA kuncinya
            inputNama.value = '';
            inputHarga.value = '';
            inputNama.readOnly = false;
            inputHarga.readOnly = false;
            inputNama.classList.remove('bg-light');
            inputHarga.classList.remove('bg-light');
        }
    });
</script>
@endsection
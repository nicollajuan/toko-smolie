<div class="modal fade" id="modalEditProduk{{ $data->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            {{-- Header Kuning (Visual Cue untuk Edit) --}}
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark" id="modalEditLabel{{ $data->id }}">Ubah Data Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('produk.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body text-start">
                    
                    {{-- Kode Produk (Read Only) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Produk</label>
                        <input type="text" class="form-control bg-light" value="{{ $data->id }}" readonly>
                    </div>

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" value="{{ $data->nama_produk }}" required>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select class="form-select" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ $data->kategori_id == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Harga & Stok --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga (Rp)</label>
                            <input type="number" class="form-control" name="harga" value="{{ $data->harga }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok</label>
                            <input type="number" class="form-control" name="stock" value="{{ $data->stock }}" required>
                        </div>
                    </div>

                    {{-- === BAGIAN PENTING: STATUS PRODUK === --}}
                    {{-- Ini solusi untuk produk yang tidak bisa dihapus --}}
                    <div class="mb-3 p-3 bg-light border rounded">
                        <label class="form-label fw-bold text-primary">Status Produk</label>
                        <select class="form-select border-primary" name="status">
                            <option value="aktif" {{ $data->status == 'aktif' ? 'selected' : '' }}>🟢 Aktif (Tampil di Menu)</option>
                            <option value="non-aktif" {{ $data->status == 'non-aktif' ? 'selected' : '' }}>🔴 Non-Aktif (Sembunyikan)</option>
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 0.85rem;">
                            <i class="bi bi-info-circle"></i> Pilih <b>"Non-Aktif"</b> jika produk sudah tidak dijual tapi pernah ada transaksi (tidak bisa dihapus).
                        </small>
                    </div>

                    {{-- Gambar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Produk</label>
                        <input type="file" class="form-control mb-2" name="gambar" accept="image/*">
                        
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted">Gambar saat ini:</small>
                            @if($data->gambar)
                                <img src="{{ asset('img/produk/'.$data->gambar) }}" width="60" height="60" class="rounded border object-fit-cover">
                            @else
                                <span class="badge bg-secondary">Belum ada gambar</span>
                            @endif
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-labelledby="modalTambahProdukLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahProdukLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{route('produk.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    
                    {{-- 1. INPUT ID MANUAL (Tetap Ada) --}}
                    <div class="mb-3">
                        <label for="id" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                        {{-- Tambahkan @error untuk memberi tahu jika ID sudah terpakai --}}
                        <input type="number" class="form-control @error('id') is-invalid @enderror" name="id" id="id" value="{{ old('id') }}" required>
                        @error('id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}" required>
                    </div>
                    
                    {{-- 2. PERBAIKAN KRUSIAL DISINI --}}
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        
                        {{-- Ubah name="kategori" menjadi name="kategori_id" --}}
                        <select name="kategori_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach ($kategori as $category)
                                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="harga" id="harga" value="{{ old('harga') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="stock" id="stock" value="{{ old('stock') }}" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
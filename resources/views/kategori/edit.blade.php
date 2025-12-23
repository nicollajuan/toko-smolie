<div class="modal fade" id="modalEditKategori{{ $data->id }}" tabindex="-1"
aria-labelledby="modalEditKategoriLabel{{ $data->id }}" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">  

    <div class="modal-header">
        <h5 classs="modal-title" id="modalEditKategoriLabel{{ $data->id }}">Edit Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form action="{{ route('kategori.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id" value="{{ $data->id }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="nama_kategori" value="{{ $data->nama_kategori }}" required>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>


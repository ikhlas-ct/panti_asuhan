@extends('layouts.user.user')

@section('title', 'Edit Panti Asuhan - ' . $pantiAsuhan->nama_panti)

@section('styles')
<style>
    .section-divider {
        background:#f8f9fa; border-left:4px solid #e96c1a;
        padding:8px 14px; border-radius:0 6px 6px 0;
        font-weight:600; font-size:.9rem; color:#e96c1a; margin-bottom:1rem;
    }
    .required-mark { color:#dc3545; }
    label { font-size:.875rem; font-weight:500; }

    /* ===== PAGE HEADER ===== */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; }
    .ph-card.edit-page::before { background:#e96c1a; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .ph-icon.edit { background:#fff4ed; color:#e96c1a; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a         { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover   { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ===== FOTO UPLOAD ===== */
    .foto-drop-zone {
        border:2px dashed #ced4da; border-radius:12px;
        padding:24px 16px; text-align:center; cursor:pointer;
        transition:border-color .2s, background .2s; background:#fafbfc;
    }
    .foto-drop-zone:hover, .foto-drop-zone.drag-over { border-color:#e96c1a; background:#fff8f5; }
    .foto-drop-zone i { font-size:1.8rem; color:#94a3b8; }

    .foto-grid { display:flex; flex-wrap:wrap; gap:12px; margin-top:12px; }
    .foto-item {
        position:relative; width:120px; border-radius:10px;
        overflow:hidden; border:2px solid #e2e8f0; background:#f8fafc;
    }
    .foto-item.existing { border-color:#a3cfbb; }
    .foto-item img { width:120px; height:90px; object-fit:cover; display:block; }
    .foto-item .foto-badge {
        position:absolute; top:4px; left:4px;
        font-size:.6rem; font-weight:700; padding:2px 6px;
        border-radius:4px; background:rgba(0,0,0,.55); color:#fff;
    }
    .foto-item .foto-remove {
        position:absolute; top:4px; right:4px;
        width:22px; height:22px; border-radius:50%;
        background:rgba(220,38,38,.85); color:#fff;
        border:none; font-size:.65rem; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
    }
    .foto-item .foto-ket { padding:4px 6px; }
    .foto-item .foto-ket input {
        font-size:.72rem; padding:3px 6px; border-radius:6px;
        border:1px solid #e2e8f0; width:100%; background:#fff;
    }
    .cover-badge { background:rgba(22,163,74,.8) !important; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card edit-page">
        <div class="ph-left">
            <div class="ph-icon edit"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <h5 class="ph-title">Edit Panti Asuhan</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('panti-asuhan.index') }}">Panti Asuhan</a></li>
                    <li><a href="{{ route('panti-asuhan.show', $pantiAsuhan) }}">{{ $pantiAsuhan->nama_panti }}</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panti-asuhan.show', $pantiAsuhan) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye me-1"></i> Detail
            </a>
            <a href="{{ route('panti-asuhan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <form action="{{ route('panti-asuhan.update', $pantiAsuhan) }}" method="POST"
              enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- Kolom Kiri --}}
                <div class="col-lg-8">

                    {{-- Identitas Panti --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-building me-2"></i>Identitas Panti</div>
                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label">Nama Panti <span class="required-mark">*</span></label>
                                    <input type="text" name="nama_panti"
                                        class="form-control @error('nama_panti') is-invalid @enderror"
                                        value="{{ old('nama_panti', $pantiAsuhan->nama_panti) }}"
                                        required maxlength="50">
                                    @error('nama_panti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat Lengkap <span class="required-mark">*</span></label>
                                    <textarea name="alamat" rows="2"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        required maxlength="100">{{ old('alamat', $pantiAsuhan->alamat) }}</textarea>
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kelurahan</label>
                                    <input type="text" name="kelurahan"
                                        class="form-control @error('kelurahan') is-invalid @enderror"
                                        value="{{ old('kelurahan', $pantiAsuhan->kelurahan) }}" maxlength="50">
                                    @error('kelurahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" name="kecamatan"
                                        class="form-control @error('kecamatan') is-invalid @enderror"
                                        value="{{ old('kecamatan', $pantiAsuhan->kecamatan) }}" maxlength="50">
                                    @error('kecamatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Kontak --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-address-book me-2"></i>Kontak</div>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nama Kontak / Ketua</label>
                                    <input type="text" name="nama_kontak"
                                        class="form-control @error('nama_kontak') is-invalid @enderror"
                                        value="{{ old('nama_kontak', $pantiAsuhan->nama_kontak) }}" maxlength="50">
                                    @error('nama_kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="no_telp"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp', $pantiAsuhan->no_telp) }}" maxlength="20">
                                    @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $pantiAsuhan->email) }}" maxlength="100">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Manajemen Foto --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-images me-2"></i>Foto Panti</div>

                            {{-- Foto yang sudah ada --}}
                            @if($pantiAsuhan->fotoPanti->count())
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Foto yang sudah tersimpan — klik <i class="fas fa-times text-danger"></i> untuk menghapus.
                                </p>
                                <div class="foto-grid mb-4">
                                    @foreach($pantiAsuhan->fotoPanti as $loop_idx => $foto)
                                    <div class="foto-item existing">
                                        <img src="{{ asset('storage/' . $foto->foto) }}" alt="{{ $foto->keterangan }}">
                                        <span class="foto-badge {{ $loop_idx === 0 ? 'cover-badge' : '' }}">
                                            {{ $loop_idx === 0 ? 'Cover' : '#' . ($loop_idx+1) }}
                                        </span>
                                        {{-- Tombol hapus foto (submit form terpisah) --}}
                                        <form action="{{ route('panti-asuhan.foto.destroy', [$pantiAsuhan, $foto]) }}"
                                              method="POST" class="btn-hapus-foto-form"
                                              data-ket="{{ $foto->keterangan ?? 'foto ini' }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="foto-remove btn-hapus-foto" title="Hapus foto">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <div class="foto-ket">
                                            <span style="font-size:.72rem;color:#64748b;">{{ $foto->keterangan ?: '-' }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-3">Belum ada foto tersimpan.</p>
                            @endif

                            {{-- Tambah foto baru --}}
                            <label class="fw-semibold text-secondary" style="font-size:.83rem;">
                                <i class="fas fa-plus me-1"></i> Tambah Foto Baru
                            </label>
                            <div class="foto-drop-zone mt-2" id="drop-zone" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-cloud-upload-alt mb-1 d-block"></i>
                                <div class="text-secondary" style="font-size:.83rem;">Klik atau seret foto ke sini</div>
                                <div class="text-muted" style="font-size:.72rem;">JPG / PNG / WEBP — maks. 2 MB per foto</div>
                            </div>
                            <input type="file" id="fotos-input" name="fotos[]" multiple accept="image/*" class="d-none">
                            @error('fotos.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                            <div class="foto-grid" id="foto-preview-grid"></div>
                        </div>
                    </div>

                </div>{{-- end col-lg-8 --}}

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Status</div>
                            <label class="form-label">Status <span class="required-mark">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif"    {{ old('status', $pantiAsuhan->status) === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $pantiAsuhan->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="4"
                                class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pantiAsuhan->keterangan) }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('panti-asuhan.show', $pantiAsuhan) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </div>{{-- end col-lg-4 --}}
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ===== Preview foto baru =====
(function () {
    const input    = document.getElementById('fotos-input');
    const grid     = document.getElementById('foto-preview-grid');
    const dropZone = document.getElementById('drop-zone');
    let dt = new DataTransfer();

    function addFiles(files) {
        [...files].forEach(file => {
            if (!file.type.match('image.*')) return;
            dt.items.add(file);
            const idx = dt.items.length - 1;
            const reader = new FileReader();
            reader.onload = e => {
                const item = document.createElement('div');
                item.className = 'foto-item';
                item.dataset.idx = idx;
                item.innerHTML = `
                    <img src="${e.target.result}" alt="">
                    <span class="foto-badge">Baru</span>
                    <button type="button" class="foto-remove"><i class="fas fa-times"></i></button>
                    <div class="foto-ket"><input type="text" name="foto_ket[]" placeholder="Keterangan (opsional)"></div>`;
                grid.appendChild(item);
                item.querySelector('.foto-remove').addEventListener('click', function () {
                    const rmIdx = parseInt(item.dataset.idx);
                    const newDt = new DataTransfer();
                    [...dt.files].forEach((f, i) => { if (i !== rmIdx) newDt.items.add(f); });
                    dt = newDt;
                    input.files = dt.files;
                    item.remove();
                    grid.querySelectorAll('.foto-item').forEach((el, i) => el.dataset.idx = i);
                });
            };
            reader.readAsDataURL(file);
        });
        input.files = dt.files;
    }

    input.addEventListener('change', function () { addFiles(this.files); });
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('drag-over'); addFiles(e.dataTransfer.files); });
})();

// ===== Hapus foto existing =====
document.querySelectorAll('.btn-hapus-foto').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.btn-hapus-foto-form');
        const ket  = form.dataset.ket;
        swal({
            title: 'Hapus Foto?',
            text: `Foto "${ket}" akan dihapus permanen.`,
            icon: 'warning',
            buttons: { cancel: 'Batal', confirm: { text: 'Hapus', className: 'btn-danger' } },
            dangerMode: true,
        }).then(ok => { if (ok) form.submit(); });
    });
});
</script>
@endsection

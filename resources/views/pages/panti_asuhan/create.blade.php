@extends('layouts.user.user')

@section('title', 'Tambah Panti Asuhan')

@section('styles')
<style>
    .section-divider {
        background:#f8f9fa; border-left:4px solid #16a34a;
        padding:8px 14px; border-radius:0 6px 6px 0;
        font-weight:600; font-size:.9rem; color:#16a34a; margin-bottom:1rem;
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
    .ph-card.create-page::before { background:#16a34a; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .ph-icon.create { background:#dcfce7; color:#16a34a; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a         { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover   { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ===== FOTO UPLOAD ===== */
    .foto-drop-zone {
        border: 2px dashed #ced4da; border-radius:12px;
        padding: 32px 16px; text-align:center; cursor:pointer;
        transition: border-color .2s, background .2s;
        background: #fafbfc;
    }
    .foto-drop-zone:hover, .foto-drop-zone.drag-over {
        border-color:#16a34a; background:#f0fdf4;
    }
    .foto-drop-zone i { font-size:2rem; color:#94a3b8; }

    .foto-grid { display:flex; flex-wrap:wrap; gap:12px; margin-top:12px; }
    .foto-item {
        position:relative; width:120px;
        border-radius:10px; overflow:hidden;
        border:2px solid #e2e8f0; background:#f8fafc;
    }
    .foto-item img { width:120px; height:90px; object-fit:cover; display:block; }
    .foto-item .foto-remove {
        position:absolute; top:4px; right:4px;
        width:22px; height:22px; border-radius:50%;
        background:rgba(220,38,38,.85); color:#fff;
        border:none; font-size:.65rem; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        line-height:1;
    }
    .foto-item .foto-ket {
        padding:4px 6px;
    }
    .foto-item .foto-ket input {
        font-size:.72rem; padding:3px 6px; border-radius:6px;
        border:1px solid #e2e8f0; width:100%; background:#fff;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card create-page">
        <div class="ph-left">
            <div class="ph-icon create"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h5 class="ph-title">Tambah Panti Asuhan</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('panti-asuhan.index') }}">Panti Asuhan</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('panti-asuhan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <form action="{{ route('panti-asuhan.store') }}" method="POST"
              enctype="multipart/form-data" novalidate id="form-panti">
            @csrf

            <div class="row g-4">

                {{-- Kolom Kiri: Data Utama --}}
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
                                        value="{{ old('nama_panti') }}"
                                        placeholder="Contoh: Panti Asuhan Al-Ikhlas" required maxlength="50">
                                    @error('nama_panti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat Lengkap <span class="required-mark">*</span></label>
                                    <textarea name="alamat" rows="2"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        placeholder="Jl. Contoh No. 123..." required maxlength="100">{{ old('alamat') }}</textarea>
                                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kelurahan</label>
                                    <input type="text" name="kelurahan"
                                        class="form-control @error('kelurahan') is-invalid @enderror"
                                        value="{{ old('kelurahan') }}" placeholder="Nama kelurahan" maxlength="50">
                                    @error('kelurahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kecamatan</label>
                                    <input type="text" name="kecamatan"
                                        class="form-control @error('kecamatan') is-invalid @enderror"
                                        value="{{ old('kecamatan') }}" placeholder="Nama kecamatan" maxlength="50">
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
                                        value="{{ old('nama_kontak') }}" placeholder="Nama PIC" maxlength="50">
                                    @error('nama_kontak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="no_telp"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp') }}" placeholder="08xx-xxxx-xxxx" maxlength="20">
                                    @error('no_telp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="email@panti.com" maxlength="100">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Foto Panti --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-images me-2"></i>Foto-Foto Panti</div>
                            <p class="text-muted small mb-3">Upload beberapa foto panti. Foto pertama akan dijadikan foto utama/cover.</p>

                            {{-- Drop zone --}}
                            <div class="foto-drop-zone" id="drop-zone" onclick="document.getElementById('fotos-input').click()">
                                <i class="fas fa-cloud-upload-alt mb-2 d-block"></i>
                                <div class="fw-semibold text-secondary" style="font-size:.85rem;">Klik atau seret foto ke sini</div>
                                <div class="text-muted" style="font-size:.75rem;">JPG / PNG / WEBP — maks. 2 MB per foto</div>
                            </div>
                            <input type="file" id="fotos-input" name="fotos[]" multiple accept="image/*" class="d-none">
                            @error('fotos.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                            {{-- Preview Grid --}}
                            <div class="foto-grid" id="foto-preview-grid"></div>
                        </div>
                    </div>

                </div>{{-- end col-lg-8 --}}

                {{-- Kolom Kanan: Status & Keterangan --}}
                <div class="col-lg-4">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Status</div>

                            <div class="mb-3">
                                <label class="form-label">Status <span class="required-mark">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif"    {{ old('status','aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status')         === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="4"
                                class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="Catatan / informasi lainnya...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Simpan Data
                        </button>
                        <a href="{{ route('panti-asuhan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </div>{{-- end col-lg-4 --}}
            </div>
        </form>
    </div>{{-- end .page-inner --}}
</div>
@endsection

@section('scripts')
<script>
(function () {
    const input    = document.getElementById('fotos-input');
    const grid     = document.getElementById('foto-preview-grid');
    const dropZone = document.getElementById('drop-zone');

    // DataTransfer untuk merebuild FileList
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
                    <button type="button" class="foto-remove" title="Hapus"><i class="fas fa-times"></i></button>
                    <div class="foto-ket">
                        <input type="text" name="foto_ket[]" placeholder="Keterangan (opsional)">
                    </div>`;
                grid.appendChild(item);

                item.querySelector('.foto-remove').addEventListener('click', function () {
                    const rmIdx = parseInt(item.dataset.idx);
                    // Rebuild DataTransfer tanpa item ini
                    const newDt = new DataTransfer();
                    [...dt.files].forEach((f, i) => { if (i !== rmIdx) newDt.items.add(f); });
                    dt = newDt;
                    input.files = dt.files;
                    // Update idx semua item
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
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        addFiles(e.dataTransfer.files);
    });
})();
</script>
@endsection

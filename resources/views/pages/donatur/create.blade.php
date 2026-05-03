@extends('layouts.user.user')

@section('title', 'Tambah Donatur')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
<style>
    /* ── Select2 theme ── */
    .select2-container--default .select2-selection--single {
        border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important;
        height: 42px !important; background: #f8fafc !important;
        display: flex !important; align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important; font-size: .85rem; color: #334155; padding-left: 13px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-radius: 8px; border: 1.5px solid #e2e8f0; font-size: .82rem; padding: 7px 11px;
    }
    .select2-results__option { font-size: .83rem; padding: 9px 12px; }
    .select2-results__option--highlighted { background: #0891b2 !important; }
    .select2-dropdown { border: 1.5px solid #e2e8f0; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.1); }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #0891b2 !important; box-shadow: 0 0 0 3px rgba(8,145,178,.12) !important;
    }
    .select2-container { width: 100% !important; }

    /* ── Page Header ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: #16a34a;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
        background: #dcfce7; color: #16a34a;
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
        margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ── Form ── */
    .section-divider {
        background: #f8f9fa; border-left: 4px solid #0891b2;
        padding: 8px 14px; border-radius: 0 6px 6px 0;
        font-weight: 600; font-size: .9rem; color: #0891b2; margin-bottom: 1rem;
    }
    .form-control {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 9px 13px; color: #334155;
        background: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus {
        border-color: #0891b2; background: #fff;
        box-shadow: 0 0 0 3px rgba(8,145,178,.1);
    }
    .form-control::placeholder { color: #94a3b8; }
    label { font-size: .875rem; font-weight: 500; }
    .required-mark { color: #dc3545; }
    .optional-hint { font-size: .72rem; color: #94a3b8; font-weight: 400; margin-left: 4px; }

    /* ── Foto ── */
    .foto-preview-wrap {
        width: 120px; height: 120px; border: 2px dashed #ced4da; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; overflow: hidden; transition: border-color .2s;
    }
    .foto-preview-wrap:hover { border-color: #0891b2; }
    .foto-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }

    /* ── Akun radio ── */
    .akun-radio-opt {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-radius: 10px; cursor: pointer;
        border: 1.5px solid #e2e8f0; background: #f8fafc;
        transition: border-color .2s, background .2s; font-size: .84rem; font-weight: 500;
    }
    .akun-radio-opt input[type=radio] { accent-color: #0891b2; }
    .akun-radio-opt.active { border-color: #0891b2; background: #f0f9ff; }
    .akun-radio-opt:hover  { border-color: #67e8f9; }
    .akun-box {
        border: 1.5px dashed #e2e8f0; border-radius: 12px;
        padding: 16px; background: #fafbfc; transition: border-color .2s;
    }
    .akun-box.active { border-color: #0891b2; background: #f0f9ff; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h5 class="ph-title">Tambah Donatur</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('donatur.index') }}">Donatur</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('donatur.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">
        <form action="{{ route('donatur.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row g-4">

                {{-- Kolom Kiri --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-id-card me-2"></i>Data Donatur</div>
                            <div class="row g-3">

                                <div class="col-md-8">
                                    <label class="form-label">Nama <span class="required-mark">*</span></label>
                                    <input type="text" name="nama" maxlength="50"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama') }}" required
                                        placeholder="Nama lengkap / nama lembaga">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Jenis Donatur <span class="required-mark">*</span></label>
                                    <select name="jenis_donatur" id="jenis_donatur"
                                        class="form-select-s2 @error('jenis_donatur') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach(['perorangan' => 'Perorangan','organisasi' => 'Organisasi','perusahaan' => 'Perusahaan','pemerintah' => 'Pemerintah'] as $val => $lbl)
                                            <option value="{{ $val }}" {{ old('jenis_donatur') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_donatur')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">No. Telepon <span class="optional-hint">(opsional)</span></label>
                                    <input type="text" name="no_telp" maxlength="20"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
                                    @error('no_telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="col-12">
                                    <label class="form-label">Alamat <span class="optional-hint">(opsional)</span></label>
                                    <textarea name="alamat" rows="2"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Keterangan <span class="optional-hint">(opsional)</span></label>
                                    <textarea name="keterangan" rows="2"
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        placeholder="Catatan tambahan…">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">

                    {{-- Foto --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="section-divider text-start"><i class="fas fa-camera me-2"></i>Foto</div>
                            <div class="foto-preview-wrap mx-auto mb-3"
                                 onclick="document.getElementById('foto-input').click()">
                                <img id="foto-preview"
                                    src="https://ui-avatars.com/api/?name=D&background=e0f2fe&color=0891b2&size=120"
                                    alt="preview">
                            </div>
                            <input type="file" name="foto" id="foto-input" accept="image/*" class="d-none">
                            <button type="button" class="btn btn-sm w-100"
                                    style="background:#e0f2fe;color:#0891b2;border-radius:9px;"
                                    onclick="document.getElementById('foto-input').click()">
                                <i class="fas fa-upload me-1"></i> Pilih Foto
                            </button>
                            <div class="text-muted small mt-2">JPG/PNG/WEBP, maks 2 MB (opsional)</div>
                            @error('foto')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Status</div>
                            <label class="form-label">Status <span class="required-mark">*</span></label>
                            <select name="status" id="select-status"
                                class="form-select-s2 @error('status') is-invalid @enderror" required>
                                <option value="aktif"    {{ old('status','aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Akun Login --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-user-shield me-2"></i>Akun Login</div>

                            <div class="d-flex flex-column gap-2 mb-3">
                                <label class="akun-radio-opt {{ old('mode_akun','none') === 'none' ? 'active' : '' }}">
                                    <input type="radio" name="mode_akun" value="none"
                                        {{ old('mode_akun','none') === 'none' ? 'checked' : '' }}>
                                    <span><i class="fas fa-ban me-1 text-muted"></i> Tanpa akun login</span>
                                </label>
                                <label class="akun-radio-opt {{ old('mode_akun') === 'baru' ? 'active' : '' }}">
                                    <input type="radio" name="mode_akun" value="baru"
                                        {{ old('mode_akun') === 'baru' ? 'checked' : '' }}>
                                    <span><i class="fas fa-user-plus me-1 text-success"></i> Buat akun baru sekaligus</span>
                                </label>
                            </div>

                            <div id="form-akun-baru" class="akun-box active"
                                 style="{{ old('mode_akun') === 'baru' ? '' : 'display:none' }}">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Username <span class="required-mark">*</span></label>
                                        <input type="text" name="akun_username"
                                            class="form-control @error('akun_username') is-invalid @enderror"
                                            value="{{ old('akun_username') }}" placeholder="Username login">
                                        @error('akun_username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email Login <span class="required-mark">*</span></label>
                                        <input type="email" name="akun_email"
                                            class="form-control @error('akun_email') is-invalid @enderror"
                                            value="{{ old('akun_email') }}" placeholder="email@domain.com">
                                        @error('akun_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Password <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="akun_password" id="pw1"
                                                class="form-control @error('akun_password') is-invalid @enderror"
                                                placeholder="Min. 8 karakter">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw1',this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('akun_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Konfirmasi Password <span class="required-mark">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="akun_password_confirmation" id="pw2"
                                                class="form-control" placeholder="Ulangi password">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw2',this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 p-2 rounded" style="background:#f0fdf4;font-size:.75rem;color:#15803d;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Akun dibuat dengan role <strong>donatur</strong> dan status <strong>aktif</strong>.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                        <a href="{{ route('donatur.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
    // Init Select2
    $('#jenis_donatur').select2({ placeholder: '-- Pilih Jenis --', allowClear: true, width: '100%' });
    $('#select-status').select2({ minimumResultsForSearch: Infinity, width: '100%' });

    // Preview foto
    document.getElementById('foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Mode akun
    const modeRadios   = document.querySelectorAll('input[name="mode_akun"]');
    const formAkunBaru = document.getElementById('form-akun-baru');
    const radioOpts    = document.querySelectorAll('.akun-radio-opt');

    function applyMode(val) {
        formAkunBaru.style.display = val === 'baru' ? '' : 'none';
        radioOpts.forEach(o => {
            o.classList.toggle('active', o.querySelector('input').checked);
        });
    }

    modeRadios.forEach(r => r.addEventListener('change', () => applyMode(r.value)));

    // Toggle password
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.innerHTML = `<i class="fas fa-${isText ? 'eye' : 'eye-slash'}"></i>`;
    }
</script>
@endsection

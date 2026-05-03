@extends('layouts.user.user')

@section('title', 'Tambah Pengurus')

@section('styles')
<style>
    /* ===== PAGE HEADER CARD ===== */
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

    /* ===== FORM ===== */
    .section-divider {
        background: #f8f9fa; border-left: 4px solid #7c3aed;
        padding: 8px 14px; border-radius: 0 6px 6px 0;
        font-weight: 600; font-size: .9rem; color: #7c3aed; margin-bottom: 1rem;
    }
    .form-control, .form-select {
        border-radius: 10px; border: 1.5px solid #e2e8f0;
        font-size: .85rem; padding: 9px 13px; color: #334155;
        background: #f8fafc; transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #7c3aed; background: #fff;
        box-shadow: 0 0 0 3px rgba(124,58,237,.1);
    }
    .form-control::placeholder { color: #94a3b8; }
    label { font-size: .875rem; font-weight: 500; }
    .required-mark { color: #dc3545; }
    .optional-hint { font-size: .72rem; color: #94a3b8; font-weight: 400; margin-left: 4px; }

    /* ===== FOTO PREVIEW ===== */
    .foto-preview-wrap {
        width: 120px; height: 120px;
        border: 2px dashed #ced4da; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; overflow: hidden; transition: border-color .2s;
    }
    .foto-preview-wrap:hover { border-color: #7c3aed; }
    .foto-preview-wrap img { width: 100%; height: 100%; object-fit: cover; }

    /* ===== AKUN BOX ===== */
    .akun-box {
        border: 1.5px dashed #e2e8f0; border-radius: 12px;
        padding: 16px; background: #fafbfc; transition: border-color .2s, background .2s;
    }
    .akun-box.active { border-color: #7c3aed; background: #faf5ff; }

    .akun-radio-opt {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-radius: 10px; cursor: pointer;
        border: 1.5px solid #e2e8f0; background: #f8fafc;
        transition: border-color .2s, background .2s;
        font-size: .84rem; font-weight: 500;
    }
    .akun-radio-opt input[type=radio] { accent-color: #7c3aed; }
    .akun-radio-opt.active { border-color: #7c3aed; background: #faf5ff; }
    .akun-radio-opt:hover  { border-color: #a78bfa; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-user-plus"></i></div>
            <div>
                <h5 class="ph-title">Tambah Pengurus</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pengurus.index') }}">Pengurus</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">
        <form action="{{ route('pengurus.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row g-4">

                {{-- Kolom Kiri --}}
                <div class="col-lg-8">

                    {{-- Data Diri --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-id-card me-2"></i>Data Diri</div>
                            <div class="row g-3">

                                @if(auth()->user()->isAdminDinsos())
                                <div class="col-12">
                                    <label class="form-label">Panti Asuhan <span class="required-mark">*</span></label>
                                    <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Panti --</option>
                                        @foreach($pantis as $panti)
                                            <option value="{{ $panti->id }}" {{ old('panti_asuhan_id') == $panti->id ? 'selected' : '' }}>
                                                {{ $panti->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('panti_asuhan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @else
                                    <input type="hidden" name="panti_asuhan_id"
                                        value="{{ auth()->user()->pengurus->panti_asuhan_id ?? '' }}">
                                @endif

                                <div class="col-md-8">
                                    <label class="form-label">Nama Lengkap <span class="required-mark">*</span></label>
                                    <input type="text" name="nama" maxlength="50"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama') }}" required placeholder="Nama lengkap pengurus">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Jenis Kelamin <span class="required-mark">*</span></label>
                                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">NIK <span class="optional-hint">(opsional)</span></label>
                                    <input type="text" name="nik" maxlength="16"
                                        class="form-control @error('nik') is-invalid @enderror"
                                        value="{{ old('nik') }}" placeholder="16 digit NIK">
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jabatan <span class="optional-hint">(opsional)</span></label>
                                    <input type="text" name="jabatan" maxlength="50"
                                        class="form-control @error('jabatan') is-invalid @enderror"
                                        value="{{ old('jabatan') }}" placeholder="Ketua, Bendahara, dll">
                                    @error('jabatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5">
                                    <label class="form-label">Tempat Lahir <span class="optional-hint">(opsional)</span></label>
                                    <input type="text" name="tempat_lahir" maxlength="100"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir') }}">
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Lahir <span class="optional-hint">(opsional)</span></label>
                                    <input type="date" name="tanggal_lahir"
                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                        value="{{ old('tanggal_lahir') }}">
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Pendidikan Terakhir <span class="optional-hint">(opsional)</span></label>
                                    <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') is-invalid @enderror">
                                        <option value="">-- Pilih --</option>
                                        @foreach(['SD','SMP','SMA/SMK','D1','D2','D3','S1','S2','S3'] as $p)
                                            <option value="{{ $p }}" {{ old('pendidikan_terakhir') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                    @error('pendidikan_terakhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Kontak --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-address-book me-2"></i>Kontak & Alamat</div>
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon <span class="optional-hint">(opsional)</span></label>
                                    <input type="text" name="no_telp" maxlength="20"
                                        class="form-control @error('no_telp') is-invalid @enderror"
                                        value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx">
                                    @error('no_telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="optional-hint">(opsional)</span></label>
                                    <input type="email" name="email" maxlength="100"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="email@domain.com">
                                    @error('email')
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

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">

                    {{-- Foto --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center">
                            <div class="section-divider text-start"><i class="fas fa-camera me-2"></i>Foto Pengurus</div>
                            <div class="foto-preview-wrap mx-auto mb-3" id="foto-wrap"
                                 onclick="document.getElementById('foto-input').click()">
                                <img id="foto-preview"
                                    src="https://ui-avatars.com/api/?name=P&background=f5f3ff&color=7c3aed&size=120"
                                    alt="preview">
                            </div>
                            <input type="file" name="foto" id="foto-input" accept="image/*" class="d-none">
                            <button type="button" class="btn btn-sm w-100"
                                    style="background:#f5f3ff;color:#7c3aed;border-radius:9px;"
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
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="aktif"    {{ old('status','aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Akun Login --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-user-shield me-2"></i>Akun Login</div>

                            {{-- Toggle --}}
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

                            {{-- Form akun baru --}}
                            <div id="form-akun-baru" style="{{ old('mode_akun') === 'baru' ? '' : 'display:none' }}"
                                 class="akun-box active">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nama Akun <span class="required-mark">*</span></label>
                                        <input type="text" name="akun_name"
                                            class="form-control @error('akun_name') is-invalid @enderror"
                                            value="{{ old('akun_name') }}"
                                            placeholder="Nama tampil untuk login">
                                        @error('akun_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email Login <span class="required-mark">*</span></label>
                                        <input type="email" name="akun_email"
                                            class="form-control @error('akun_email') is-invalid @enderror"
                                            value="{{ old('akun_email') }}"
                                            placeholder="email@domain.com">
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
                                                class="form-control"
                                                placeholder="Ulangi password">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw2',this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 p-2 rounded" style="background:#f0fdf4;font-size:.75rem;color:#15803d;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Akun akan dibuat dengan role <strong>admin_panti</strong> dan status <strong>aktif</strong>.
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                        <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview foto
    document.getElementById('foto-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('foto-preview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Mode akun radio
    const modeRadios   = document.querySelectorAll('input[name="mode_akun"]');
    const formAkunBaru = document.getElementById('form-akun-baru');
    const radioOpts    = document.querySelectorAll('.akun-radio-opt');

    function applyMode(val) {
        formAkunBaru.style.display = val === 'baru' ? '' : 'none';
        radioOpts.forEach(opt => {
            const radio = opt.querySelector('input[type=radio]');
            opt.classList.toggle('active', radio.checked);
        });
    }

    modeRadios.forEach(r => r.addEventListener('change', () => applyMode(r.value)));

    // Toggle show/hide password
    function togglePw(id, btn) {
        const input = document.getElementById(id);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.innerHTML = `<i class="fas fa-${isText ? 'eye' : 'eye-slash'}"></i>`;
    }
</script>
@endsection

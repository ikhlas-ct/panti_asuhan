@extends('layouts.user.user')

@section('title', 'Tambah Transaksi Keuangan')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Page Header ── */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#1a73e8; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#e8f0fe; color:#1a73e8; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; margin-top:4px; list-style:none; padding:0; margin-bottom:0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Form ── */
    label { font-size:.875rem; font-weight:500; margin-bottom:4px; display:block; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0;
        font-size:.85rem; padding:8px 12px; color:#334155;
        background:#f8fafc; transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:#1a73e8; background:#fff;
        box-shadow:0 0 0 3px rgba(26,115,232,.12); outline:none;
    }
    .input-group-text {
        background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none;
        border-radius:10px 0 0 10px; color:#94a3b8; font-size:.85rem;
    }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

    /* ── Section divider ── */
    .section-divider {
        background:#f8f9fa; border-left:4px solid #1a73e8;
        padding:8px 14px; border-radius:0 6px 6px 0;
        font-weight:600; font-size:.88rem; color:#1a73e8; margin-bottom:1.1rem;
    }
    .section-divider.green  { border-left-color:#16a34a; color:#15803d; background:#f0fdf4; }
    .section-divider.orange { border-left-color:#ea580c; color:#c2410c; background:#fff7ed; }

    /* ── SUMBER TOGGLE ── */
    .sumber-wrap { display:flex; gap:12px; flex-wrap:wrap; }
    .sumber-card {
        flex:1; min-width:160px; border:2px solid #e2e8f0; border-radius:14px;
        padding:14px 16px; cursor:pointer; background:#f8fafc;
        transition:all .2s; display:flex; align-items:center; gap:12px;
    }
    .sumber-card:hover { border-color:#1a73e8; background:#eff6ff; }
    .sumber-card.active-manual      { border-color:#ea580c; background:#fff7ed; }
    .sumber-card.active-donasi      { border-color:#16a34a; background:#f0fdf4; }
    .sumber-card input[type=radio]  { display:none; }
    .sumber-icon {
        width:38px; height:38px; border-radius:10px; display:flex;
        align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;
    }
    .sumber-icon.manual { background:#ffedd5; color:#ea580c; }
    .sumber-icon.donasi { background:#dcfce7; color:#16a34a; }
    .sumber-label { font-weight:700; font-size:.88rem; color:#1e293b; line-height:1.2; }
    .sumber-sub   { font-size:.74rem; color:#64748b; margin-top:2px; }

    /* ── JENIS TOGGLE ── */
    .jenis-btn {
        border-radius:10px; border:2px solid #e2e8f0; background:#f8fafc;
        color:#64748b; font-weight:600; font-size:.85rem; padding:8px 18px;
        cursor:pointer; transition:all .2s;
    }
    .jenis-btn.active-pemasukan  { border-color:#16a34a; background:#dcfce7; color:#15803d; }
    .jenis-btn.active-pengeluaran { border-color:#dc2626; background:#fee2e2; color:#dc2626; }

    /* ── METODE TOGGLE ── */
    .metode-btn {
        border-radius:10px; border:2px solid #e2e8f0; background:#f8fafc;
        color:#64748b; font-weight:600; font-size:.83rem; padding:7px 16px;
        cursor:pointer; transition:all .2s;
    }
    .metode-btn.active { border-color:#1a73e8; background:#eff6ff; color:#1a73e8; }

    /* ── Bukti preview ── */
    .bukti-preview { display:none; margin-top:10px; }
    .bukti-preview img { max-width:160px; border-radius:10px; border:2px solid #e2e8f0; }
    .bukti-preview.show { display:block; }

    /* ── BTN ── */
    .btn-primary {
        background:linear-gradient(135deg,#1a73e8,#1558b0); border:none;
        border-radius:10px; font-weight:600; font-size:.85rem; padding:9px 20px;
        box-shadow:0 2px 8px rgba(26,115,232,.35); transition:all .2s;
    }
    .btn-primary:hover { background:linear-gradient(135deg,#1558b0,#0f3e82); transform:translateY(-1px); }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; }
    .btn-outline-secondary:hover { background:#f1f5f9; }

    /* ── Animasi section ── */
    .section-anim { transition: opacity .25s, max-height .3s; overflow:hidden; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h5 class="ph-title">Tambah Transaksi Keuangan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('keuangan.index') }}">Keuangan</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">
    <form action="{{ route('keuangan.store') }}" method="POST"
          enctype="multipart/form-data" id="form-keuangan" novalidate>
        @csrf

        <div class="row g-4">

            {{-- ═══════════ KOLOM KIRI ═══════════ --}}
            <div class="col-lg-8">

                {{-- ── CARD 1: Info Dasar ── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</div>
                        <div class="row g-3">

                            {{-- Panti (admin_dinsos only) --}}
                            @unless($isAdminPanti)
                            <div class="col-12">
                                <label>Panti Asuhan <span class="required-mark">*</span></label>
                                <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Panti Asuhan --</option>
                                    @foreach($pantis as $p)
                                        <option value="{{ $p->id }}" {{ old('panti_asuhan_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_panti }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('panti_asuhan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endunless

                            {{-- SUMBER TRANSAKSI --}}
                            <div class="col-12">
                                <label>Sumber Transaksi <span class="required-mark">*</span></label>
                                <div class="sumber-wrap">

                                    {{-- Manual --}}
                                    <label class="sumber-card {{ old('sumber', 'manual') === 'manual' ? 'active-manual' : '' }}"
                                           id="card-manual" onclick="setSumber('manual')">
                                        <input type="radio" name="sumber" value="manual"
                                               {{ old('sumber', 'manual') === 'manual' ? 'checked' : '' }}>
                                        <div class="sumber-icon manual"><i class="fas fa-pen"></i></div>
                                        <div>
                                            <div class="sumber-label">Manual</div>
                                            <div class="sumber-sub">Pengeluaran atau pemasukan non-donasi</div>
                                        </div>
                                    </label>

                                    {{-- Donasi --}}
                                    <label class="sumber-card {{ old('sumber') === 'donasi' ? 'active-donasi' : '' }}"
                                           id="card-donasi" onclick="setSumber('donasi')">
                                        <input type="radio" name="sumber" value="donasi"
                                               {{ old('sumber') === 'donasi' ? 'checked' : '' }}>
                                        <div class="sumber-icon donasi"><i class="fas fa-hand-holding-heart"></i></div>
                                        <div>
                                            <div class="sumber-label">Dari Donasi</div>
                                            <div class="sumber-sub">Pemasukan dari donatur, 2 tabel tersimpan</div>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            {{-- Jenis Transaksi (hanya tampil jika manual) --}}
                            <div class="col-12" id="section-jenis">
                                <label>Jenis Transaksi <span class="required-mark">*</span></label>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="jenis-btn {{ old('jenis', 'pengeluaran') === 'pengeluaran' ? 'active-pengeluaran' : '' }}"
                                        id="btn-pengeluaran" onclick="setJenis('pengeluaran')">
                                        <i class="fas fa-arrow-circle-up me-1"></i> Pengeluaran
                                    </button>
                                    <button type="button"
                                        class="jenis-btn {{ old('jenis', 'pengeluaran') === 'pemasukan' ? 'active-pemasukan' : '' }}"
                                        id="btn-pemasukan" onclick="setJenis('pemasukan')">
                                        <i class="fas fa-arrow-circle-down me-1"></i> Pemasukan
                                    </button>
                                </div>
                                <input type="hidden" name="jenis" id="jenis" value="{{ old('jenis', 'pengeluaran') }}">
                                @error('jenis')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-md-6">
                                <label>Tanggal <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', date('Y-m-d')) }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="col-md-6">
                                <label>Kategori</label>
                                <input type="text" name="kategori" id="field-kategori"
                                    class="form-control @error('kategori') is-invalid @enderror"
                                    value="{{ old('kategori') }}"
                                    placeholder="Contoh: Operasional, Donasi Uang..." maxlength="50"
                                    list="kategori-list">
                                <datalist id="kategori-list">
                                    <option value="Donasi Uang">
                                    <option value="Operasional">
                                    <option value="Gaji Pengurus">
                                    <option value="Konsumsi">
                                    <option value="Pendidikan">
                                    <option value="Kesehatan">
                                    <option value="Perlengkapan">
                                    <option value="Renovasi">
                                </datalist>
                                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Nominal --}}
                            <div class="col-md-6">
                                <label>Nominal <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="nominal"
                                        class="form-control @error('nominal') is-invalid @enderror"
                                        value="{{ old('nominal') }}" placeholder="0" min="1" required>
                                </div>
                                @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Keterangan --}}
                            <div class="col-12">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    value="{{ old('keterangan') }}"
                                    placeholder="Deskripsi singkat transaksi..." maxlength="255">
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── CARD 2: Data Donasi (tampil jika sumber = donasi) ── --}}
                <div class="card shadow-sm mb-4 section-anim" id="section-donasi"
                     style="{{ old('sumber') === 'donasi' ? '' : 'display:none' }}">
                    <div class="card-body">
                        <div class="section-divider green">
                            <i class="fas fa-hand-holding-heart me-2"></i>Data Donasi
                        </div>
                        <div class="row g-3">

                            {{-- Pilih Donatur --}}
                            <div class="col-12">
                                <label>Donatur <span class="required-mark">*</span></label>
                                <select name="donatur_id" id="donatur_id"
                                    class="form-select @error('donatur_id') is-invalid @enderror">
                                    <option value="">-- Pilih Donatur --</option>
                                    @foreach($donaturList as $d)
                                        <option value="{{ $d->id }}"
                                            data-jenis="{{ $d->jenis_donatur }}"
                                            {{ old('donatur_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }}
                                            <span class="text-muted">({{ ucfirst($d->jenis_donatur) }})</span>
                                        </option>
                                    @endforeach
                                </select>
                                @error('donatur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="mt-1">
                                    <small class="text-muted">Donatur belum ada?
                                        <a href="{{ route('donatur.create') }}" target="_blank" class="text-primary">
                                            Tambah donatur baru <i class="fas fa-external-link-alt" style="font-size:.65rem;"></i>
                                        </a>
                                    </small>
                                </div>
                            </div>

                            {{-- Metode --}}
                            <div class="col-12">
                                <label>Metode Donasi <span class="required-mark">*</span></label>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="metode-btn {{ old('metode', 'online') === 'online' ? 'active' : '' }}"
                                        id="btn-online" onclick="setMetode('online')">
                                        <i class="fas fa-globe me-1"></i> Online (Transfer/QRIS)
                                    </button>
                                    <button type="button"
                                        class="metode-btn {{ old('metode') === 'kunjungan' ? 'active' : '' }}"
                                        id="btn-kunjungan" onclick="setMetode('kunjungan')">
                                        <i class="fas fa-walking me-1"></i> Kunjungan Langsung
                                    </button>
                                </div>
                                <input type="hidden" name="metode" id="metode" value="{{ old('metode', 'online') }}">
                                @error('metode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tanggal Kunjungan (tampil jika metode = kunjungan) --}}
                            <div class="col-md-6 section-anim" id="section-tgl-kunjungan"
                                 style="{{ old('metode') === 'kunjungan' ? '' : 'display:none' }}">
                                <label>Tanggal Kunjungan <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal_kunjungan"
                                    class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                                    value="{{ old('tanggal_kunjungan') }}">
                                @error('tanggal_kunjungan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Catatan Donatur --}}
                            <div class="col-12">
                                <label>Catatan Donatur</label>
                                <textarea name="catatan" rows="2"
                                    class="form-control @error('catatan') is-invalid @enderror"
                                    placeholder="Pesan atau catatan dari donatur (opsional)..." maxlength="500"
                                    >{{ old('catatan') }}</textarea>
                                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ═══════════ KOLOM KANAN ═══════════ --}}
            <div class="col-lg-4">

                {{-- ── Bukti Keuangan ── --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-image me-2"></i>Bukti Transaksi</div>
                        <p class="text-muted small mb-2">Kwitansi / bukti serah terima (opsional).</p>
                        <input type="file" name="bukti" id="bukti-input"
                            class="form-control @error('bukti') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        @error('bukti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="bukti-preview" id="bukti-preview">
                            <img src="" id="bukti-img" alt="preview">
                        </div>
                    </div>
                </div>

                {{-- ── Bukti Transfer (hanya muncul jika donasi + metode online) ── --}}
                <div class="card shadow-sm mb-3 section-anim" id="section-bukti-transfer"
                     style="{{ (old('sumber') === 'donasi' && old('metode', 'online') === 'online') ? '' : 'display:none' }}">
                    <div class="card-body">
                        <div class="section-divider orange">
                            <i class="fas fa-receipt me-2"></i>Bukti Transfer / QRIS
                        </div>
                        <p class="text-muted small mb-2">Upload screenshot / foto bukti transfer donatur.</p>
                        <input type="file" name="bukti_transfer" id="bukti-transfer-input"
                            class="form-control @error('bukti_transfer') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        @error('bukti_transfer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="bukti-preview" id="bt-preview">
                            <img src="" id="bt-img" alt="preview bukti transfer">
                        </div>
                    </div>
                </div>

                {{-- ── Tombol ── --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Transaksi
                    </button>
                    <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </div>{{-- end col-lg-4 --}}
        </div>
    </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ── Sumber toggle ────────────────────────────────────────────
function setSumber(val) {
    const isDonasi = val === 'donasi';

    // Radio
    document.querySelectorAll('input[name=sumber]').forEach(r => r.checked = (r.value === val));

    // Card style
    document.getElementById('card-manual').className = 'sumber-card' + (val === 'manual' ? ' active-manual' : '');
    document.getElementById('card-donasi').className = 'sumber-card' + (isDonasi ? ' active-donasi' : '');

    // Section donasi
    const secDonasi = document.getElementById('section-donasi');
    secDonasi.style.display = isDonasi ? '' : 'none';

    // Section jenis (hanya untuk manual)
    document.getElementById('section-jenis').style.display = isDonasi ? 'none' : '';

    // Paksa jenis = pemasukan jika donasi
    if (isDonasi) {
        document.getElementById('jenis').value = 'pemasukan';
        // Kategori default
        const katInput = document.getElementById('field-kategori');
        if (!katInput.value) katInput.value = 'Donasi Uang';
    }

    // Bukti transfer: tampil hanya donasi + online
    updateBuktiTransfer();
}

// ── Jenis toggle ─────────────────────────────────────────────
function setJenis(val) {
    document.getElementById('jenis').value = val;
    document.getElementById('btn-pemasukan').className  = 'jenis-btn' + (val === 'pemasukan'   ? ' active-pemasukan'   : '');
    document.getElementById('btn-pengeluaran').className = 'jenis-btn' + (val === 'pengeluaran' ? ' active-pengeluaran' : '');
}

// ── Metode toggle ────────────────────────────────────────────
function setMetode(val) {
    document.getElementById('metode').value = val;
    document.getElementById('btn-online').className    = 'metode-btn' + (val === 'online'    ? ' active' : '');
    document.getElementById('btn-kunjungan').className = 'metode-btn' + (val === 'kunjungan' ? ' active' : '');

    // Tanggal kunjungan
    document.getElementById('section-tgl-kunjungan').style.display = val === 'kunjungan' ? '' : 'none';

    updateBuktiTransfer();
}

function updateBuktiTransfer() {
    const isDonasi = document.querySelector('input[name=sumber]:checked')?.value === 'donasi';
    const isOnline = document.getElementById('metode').value === 'online';
    document.getElementById('section-bukti-transfer').style.display = (isDonasi && isOnline) ? '' : 'none';
}

// ── Preview gambar ───────────────────────────────────────────
function setupPreview(inputId, imgId, wrapId) {
    document.getElementById(inputId)?.addEventListener('change', function () {
        const wrap = document.getElementById(wrapId);
        const img  = document.getElementById(imgId);
        if (this.files[0]) {
            const r = new FileReader();
            r.onload = e => { img.src = e.target.result; wrap.classList.add('show'); };
            r.readAsDataURL(this.files[0]);
        } else {
            wrap.classList.remove('show');
        }
    });
}
setupPreview('bukti-input', 'bukti-img', 'bukti-preview');
setupPreview('bukti-transfer-input', 'bt-img', 'bt-preview');

// ── Init ─────────────────────────────────────────────────────
const initSumber = document.querySelector('input[name=sumber]:checked')?.value || 'manual';
setSumber(initSumber);

const initMetode = document.getElementById('metode').value || 'online';
setMetode(initMetode);

const initJenis = document.getElementById('jenis').value || 'pengeluaran';
setJenis(initJenis);
</script>
@endsection

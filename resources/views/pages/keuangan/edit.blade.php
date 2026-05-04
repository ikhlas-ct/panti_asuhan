@extends('layouts.user.user')

@section('title', 'Edit Transaksi Keuangan')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#ca8a04; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#fef9c3; color:#ca8a04; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; margin-top:4px; list-style:none; padding:0; margin-bottom:0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    label { font-size:.875rem; font-weight:500; margin-bottom:4px; display:block; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
        padding:8px 12px; color:#334155; background:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:#ca8a04; background:#fff;
        box-shadow:0 0 0 3px rgba(202,138,4,.12); outline:none;
    }
    .input-group-text {
        background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none;
        border-radius:10px 0 0 10px; color:#94a3b8; font-size:.85rem;
    }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

    .section-divider {
        background:#f8f9fa; border-left:4px solid #ca8a04;
        padding:8px 14px; border-radius:0 6px 6px 0;
        font-weight:600; font-size:.88rem; color:#ca8a04; margin-bottom:1.1rem;
    }
    .section-divider.blue   { border-left-color:#1a73e8; color:#1a73e8; background:#eff6ff; }
    .section-divider.green  { border-left-color:#16a34a; color:#15803d; background:#f0fdf4; }
    .section-divider.orange { border-left-color:#ea580c; color:#c2410c; background:#fff7ed; }

    /* Sumber badge — di edit, sumber tidak bisa diganti, hanya tampil info */
    .sumber-badge {
        display:inline-flex; align-items:center; gap:8px;
        padding:8px 16px; border-radius:10px; font-size:.85rem; font-weight:600;
    }
    .sumber-badge.donasi  { background:#dcfce7; color:#15803d; border:1.5px solid #bbf7d0; }
    .sumber-badge.manual  { background:#fff7ed; color:#c2410c; border:1.5px solid #fed7aa; }

    .jenis-btn {
        border-radius:10px; border:2px solid #e2e8f0; background:#f8fafc;
        color:#64748b; font-weight:600; font-size:.85rem; padding:8px 18px; cursor:pointer; transition:all .2s;
    }
    .jenis-btn.active-pemasukan  { border-color:#16a34a; background:#dcfce7; color:#15803d; }
    .jenis-btn.active-pengeluaran { border-color:#dc2626; background:#fee2e2; color:#dc2626; }

    .metode-btn {
        border-radius:10px; border:2px solid #e2e8f0; background:#f8fafc;
        color:#64748b; font-weight:600; font-size:.83rem; padding:7px 16px; cursor:pointer; transition:all .2s;
    }
    .metode-btn.active { border-color:#1a73e8; background:#eff6ff; color:#1a73e8; }

    .bukti-existing { max-width:150px; border-radius:10px; border:2px solid #e2e8f0; display:block; margin-bottom:8px; }
    .bukti-preview { display:none; margin-top:10px; }
    .bukti-preview img { max-width:160px; border-radius:10px; border:2px solid #e2e8f0; }
    .bukti-preview.show { display:block; }

    .btn-warning { background:linear-gradient(135deg,#f59e0b,#d97706); border:none; border-radius:10px; font-weight:600; font-size:.85rem; padding:9px 20px; color:#fff; transition:all .2s; }
    .btn-warning:hover { background:linear-gradient(135deg,#d97706,#b45309); color:#fff; transform:translateY(-1px); }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; }

    .section-anim { transition:opacity .25s; overflow:hidden; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Transaksi Keuangan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('keuangan.index') }}">Keuangan</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">

    @php $isDonasi = $keuangan->donasi_id !== null; @endphp

    <form action="{{ route('keuangan.update', $keuangan) }}" method="POST"
          enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        {{-- Hidden: sumber tidak bisa berubah di edit --}}
        <input type="hidden" name="sumber" value="{{ $isDonasi ? 'donasi' : 'manual' }}">

        <div class="row g-4">

            {{-- ═══════════ KOLOM KIRI ═══════════ --}}
            <div class="col-lg-8">

                {{-- ── CARD 1: Info Dasar ── --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-divider blue"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</div>
                        <div class="row g-3">

                            {{-- Sumber (hanya info, tidak bisa diubah) --}}
                            <div class="col-12">
                                <label>Sumber Transaksi</label>
                                <div>
                                    @if($isDonasi)
                                        <span class="sumber-badge donasi">
                                            <i class="fas fa-hand-holding-heart"></i>
                                            Dari Donasi — tidak dapat diubah
                                        </span>
                                    @else
                                        <span class="sumber-badge manual">
                                            <i class="fas fa-pen"></i>
                                            Manual — tidak dapat diubah
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Panti (admin_dinsos only) --}}
                            @unless($isAdminPanti)
                            <div class="col-12">
                                <label>Panti Asuhan <span class="required-mark">*</span></label>
                                <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Panti Asuhan --</option>
                                    @foreach($pantis as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('panti_asuhan_id', $keuangan->panti_asuhan_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_panti }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('panti_asuhan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @endunless

                            {{-- Jenis (hanya manual) --}}
                            @unless($isDonasi)
                            <div class="col-12">
                                <label>Jenis Transaksi <span class="required-mark">*</span></label>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="jenis-btn {{ old('jenis', $keuangan->jenis) === 'pengeluaran' ? 'active-pengeluaran' : '' }}"
                                        id="btn-pengeluaran" onclick="setJenis('pengeluaran')">
                                        <i class="fas fa-arrow-circle-up me-1"></i> Pengeluaran
                                    </button>
                                    <button type="button"
                                        class="jenis-btn {{ old('jenis', $keuangan->jenis) === 'pemasukan' ? 'active-pemasukan' : '' }}"
                                        id="btn-pemasukan" onclick="setJenis('pemasukan')">
                                        <i class="fas fa-arrow-circle-down me-1"></i> Pemasukan
                                    </button>
                                </div>
                                <input type="hidden" name="jenis" id="jenis" value="{{ old('jenis', $keuangan->jenis) }}">
                                @error('jenis')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            @else
                                {{-- Donasi selalu pemasukan --}}
                                <input type="hidden" name="jenis" value="pemasukan">
                            @endunless

                            {{-- Tanggal --}}
                            <div class="col-md-6">
                                <label>Tanggal <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal"
                                    class="form-control @error('tanggal') is-invalid @enderror"
                                    value="{{ old('tanggal', $keuangan->tanggal?->format('Y-m-d')) }}" required>
                                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="col-md-6">
                                <label>Kategori</label>
                                <input type="text" name="kategori"
                                    class="form-control @error('kategori') is-invalid @enderror"
                                    value="{{ old('kategori', $keuangan->kategori) }}"
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
                                        value="{{ old('nominal', $keuangan->nominal) }}"
                                        placeholder="0" min="1" required>
                                </div>
                                @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Keterangan --}}
                            <div class="col-12">
                                <label>Keterangan</label>
                                <input type="text" name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    value="{{ old('keterangan', $keuangan->keterangan) }}"
                                    placeholder="Deskripsi singkat transaksi..." maxlength="255">
                                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── CARD 2: Data Donasi (jika sumber donasi) ── --}}
                @if($isDonasi)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-divider green">
                            <i class="fas fa-hand-holding-heart me-2"></i>Data Donasi
                        </div>
                        <div class="row g-3">

                            {{-- Pilih Donatur --}}
                            <div class="col-12">
                                <label>Donatur <span class="required-mark">*</span></label>
                                <select name="donatur_id"
                                    class="form-select @error('donatur_id') is-invalid @enderror">
                                    <option value="">-- Pilih Donatur --</option>
                                    @foreach($donaturList as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('donatur_id', $keuangan->donasi?->donatur_id) == $d->id ? 'selected' : '' }}>
                                            {{ $d->nama }} ({{ ucfirst($d->jenis_donatur) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('donatur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Metode --}}
                            <div class="col-12">
                                <label>Metode Donasi <span class="required-mark">*</span></label>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="metode-btn {{ old('metode', $keuangan->donasi?->metode ?? 'online') === 'online' ? 'active' : '' }}"
                                        id="btn-online" onclick="setMetode('online')">
                                        <i class="fas fa-globe me-1"></i> Online (Transfer/QRIS)
                                    </button>
                                    <button type="button"
                                        class="metode-btn {{ old('metode', $keuangan->donasi?->metode) === 'kunjungan' ? 'active' : '' }}"
                                        id="btn-kunjungan" onclick="setMetode('kunjungan')">
                                        <i class="fas fa-walking me-1"></i> Kunjungan Langsung
                                    </button>
                                </div>
                                <input type="hidden" name="metode" id="metode"
                                    value="{{ old('metode', $keuangan->donasi?->metode ?? 'online') }}">
                                @error('metode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tanggal Kunjungan --}}
                            <div class="col-md-6 section-anim" id="section-tgl-kunjungan"
                                 style="{{ old('metode', $keuangan->donasi?->metode) === 'kunjungan' ? '' : 'display:none' }}">
                                <label>Tanggal Kunjungan <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal_kunjungan"
                                    class="form-control @error('tanggal_kunjungan') is-invalid @enderror"
                                    value="{{ old('tanggal_kunjungan', $keuangan->donasi?->tanggal_kunjungan?->format('Y-m-d')) }}">
                                @error('tanggal_kunjungan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Catatan --}}
                            <div class="col-12">
                                <label>Catatan Donatur</label>
                                <textarea name="catatan" rows="2"
                                    class="form-control @error('catatan') is-invalid @enderror"
                                    placeholder="Catatan dari donatur (opsional)..." maxlength="500"
                                    >{{ old('catatan', $keuangan->donasi?->catatan) }}</textarea>
                                @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- end col-lg-8 --}}

            {{-- ═══════════ KOLOM KANAN ═══════════ --}}
            <div class="col-lg-4">

                {{-- Bukti Keuangan --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="section-divider blue"><i class="fas fa-file-image me-2"></i>Bukti Transaksi</div>

                        @if($keuangan->bukti)
                        <p class="text-muted small mb-1">Bukti saat ini:</p>
                        <img src="{{ asset('storage/' . $keuangan->bukti) }}" alt="Bukti" class="bukti-existing">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="hapus_bukti" id="hapus_bukti" class="form-check-input" value="1">
                            <label for="hapus_bukti" class="form-check-label text-danger small">Hapus bukti ini</label>
                        </div>
                        @endif

                        <p class="text-muted small mb-2">{{ $keuangan->bukti ? 'Ganti bukti (opsional):' : 'Upload bukti (opsional):' }}</p>
                        <input type="file" name="bukti" id="bukti-input"
                            class="form-control @error('bukti') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        @error('bukti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="bukti-preview" id="bukti-preview">
                            <img src="" id="bukti-img" alt="preview">
                        </div>
                    </div>
                </div>

                {{-- Bukti Transfer (hanya donasi + online) --}}
                @if($isDonasi)
                <div class="card shadow-sm mb-3 section-anim" id="section-bukti-transfer"
                     style="{{ ($keuangan->donasi?->metode ?? 'online') === 'online' ? '' : 'display:none' }}">
                    <div class="card-body">
                        <div class="section-divider orange"><i class="fas fa-receipt me-2"></i>Bukti Transfer / QRIS</div>

                        @if($keuangan->donasi?->bukti_transfer)
                        <p class="text-muted small mb-1">Bukti transfer saat ini:</p>
                        <img src="{{ asset('storage/' . $keuangan->donasi->bukti_transfer) }}"
                             alt="Bukti Transfer" class="bukti-existing">
                        @endif

                        <p class="text-muted small mb-2">{{ $keuangan->donasi?->bukti_transfer ? 'Ganti (opsional):' : 'Upload (opsional):' }}</p>
                        <input type="file" name="bukti_transfer" id="bukti-transfer-input"
                            class="form-control @error('bukti_transfer') is-invalid @enderror"
                            accept="image/jpg,image/jpeg,image/png,image/webp">
                        @error('bukti_transfer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="bukti-preview" id="bt-preview">
                            <img src="" id="bt-img" alt="preview bukti transfer">
                        </div>
                    </div>
                </div>
                @endif

                {{-- Tombol --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i> Update Transaksi
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
function setJenis(val) {
    document.getElementById('jenis').value = val;
    document.getElementById('btn-pemasukan').className  = 'jenis-btn' + (val === 'pemasukan'   ? ' active-pemasukan'   : '');
    document.getElementById('btn-pengeluaran').className = 'jenis-btn' + (val === 'pengeluaran' ? ' active-pengeluaran' : '');
}

function setMetode(val) {
    document.getElementById('metode').value = val;
    document.getElementById('btn-online').className    = 'metode-btn' + (val === 'online'    ? ' active' : '');
    document.getElementById('btn-kunjungan').className = 'metode-btn' + (val === 'kunjungan' ? ' active' : '');
    document.getElementById('section-tgl-kunjungan').style.display = val === 'kunjungan' ? '' : 'none';

    const secBT = document.getElementById('section-bukti-transfer');
    if (secBT) secBT.style.display = val === 'online' ? '' : 'none';
}

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

// Init jenis
const initJenis = document.getElementById('jenis')?.value;
if (initJenis) setJenis(initJenis);
</script>
@endsection

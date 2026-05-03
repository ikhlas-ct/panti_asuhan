@extends('layouts.user.user')

@section('title', 'Edit Transaksi Keuangan')

@section('styles')
<style>
    .section-divider {
        background:#f8f9fa; border-left:4px solid #ca8a04;
        padding:8px 14px; border-radius:0 6px 6px 0;
        font-weight:600; font-size:.9rem; color:#ca8a04; margin-bottom:1rem;
    }
    .required-mark { color:#dc3545; }
    label { font-size:.875rem; font-weight:500; }

    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; }
    .ph-card.edit-page::before { background:#ca8a04; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .ph-icon.edit { background:#fef9c3; color:#ca8a04; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .donasi-info-box {
        background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px;
        padding:12px 16px; font-size:.83rem; color:#1d4ed8; display:none;
    }
    .donasi-info-box.show { display:block; }

    .jenis-btn { border-radius:10px; border:2px solid #e2e8f0; background:#f8fafc; color:#64748b; font-weight:600; font-size:.85rem; padding:8px 18px; cursor:pointer; transition:all .2s; }
    .jenis-btn.active-pemasukan { border-color:#16a34a; background:#dcfce7; color:#15803d; }
    .jenis-btn.active-pengeluaran { border-color:#dc2626; background:#fee2e2; color:#dc2626; }

    .bukti-existing { max-width:160px; border-radius:10px; border:2px solid #e2e8f0; display:block; margin-bottom:8px; }
    .bukti-preview { display:none; margin-top:10px; }
    .bukti-preview img { max-width:160px; border-radius:10px; border:2px solid #e2e8f0; }
    .bukti-preview.show { display:block; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card edit-page">
        <div class="ph-left">
            <div class="ph-icon edit"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Transaksi Keuangan</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('keuangan.index') }}">Keuangan</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <form action="{{ route('keuangan.update', $keuangan) }}" method="POST"
              enctype="multipart/form-data" novalidate id="form-keuangan">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- Kolom Kiri --}}
                <div class="col-lg-8">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-exchange-alt me-2"></i>Informasi Transaksi</div>
                            <div class="row g-3">

                                {{-- Panti Asuhan (hanya admin_dinsos) --}}
                                @unless($isAdminPanti)
                                <div class="col-12">
                                    <label class="form-label">Panti Asuhan <span class="required-mark">*</span></label>
                                    <select name="panti_asuhan_id" id="panti_asuhan_id"
                                        class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
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

                                {{-- Jenis --}}
                                <div class="col-12">
                                    <label class="form-label">Jenis Transaksi <span class="required-mark">*</span></label>
                                    <div class="d-flex gap-2">
                                        <button type="button"
                                            class="jenis-btn {{ old('jenis', $keuangan->jenis) === 'pemasukan' ? 'active-pemasukan' : '' }}"
                                            id="btn-pemasukan" onclick="setJenis('pemasukan')">
                                            <i class="fas fa-arrow-circle-down me-1"></i> Pemasukan
                                        </button>
                                        <button type="button"
                                            class="jenis-btn {{ old('jenis', $keuangan->jenis) === 'pengeluaran' ? 'active-pengeluaran' : '' }}"
                                            id="btn-pengeluaran" onclick="setJenis('pengeluaran')">
                                            <i class="fas fa-arrow-circle-up me-1"></i> Pengeluaran
                                        </button>
                                    </div>
                                    <input type="hidden" name="jenis" id="jenis" value="{{ old('jenis', $keuangan->jenis) }}">
                                    @error('jenis')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                {{-- Link dari Donasi --}}
                                <div id="section-donasi" class="col-12"
                                    style="{{ old('jenis', $keuangan->jenis) === 'pengeluaran' ? 'display:none' : '' }}">
                                    <label class="form-label">Pemasukan dari Donasi
                                        <span class="text-muted fw-normal">(opsional)</span>
                                    </label>
                                    <select name="donasi_id" id="donasi_id"
                                        class="form-select @error('donasi_id') is-invalid @enderror">
                                        <option value="">-- Tidak dari donasi / pilih donasi --</option>
                                        @foreach($donasiList as $d)
                                            <option value="{{ $d->id }}"
                                                data-nominal="{{ $d->nominal }}"
                                                {{ old('donasi_id', $keuangan->donasi_id) == $d->id ? 'selected' : '' }}>
                                                {{ $d->donatur->nama ?? '-' }} —
                                                Rp {{ number_format($d->nominal, 0, ',', '.') }}
                                                ({{ \Carbon\Carbon::parse($d->tanggal_donasi)->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('donasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                    <div class="donasi-info-box mt-2 {{ $keuangan->donasi_id ? 'show' : '' }}" id="donasi-info">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Nominal & keterangan akan diisi otomatis dari data donasi terpilih.
                                    </div>
                                </div>

                                {{-- Tanggal --}}
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal <span class="required-mark">*</span></label>
                                    <input type="date" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', $keuangan->tanggal?->format('Y-m-d')) }}" required>
                                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Kategori --}}
                                <div class="col-md-6">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" name="kategori"
                                        class="form-control @error('kategori') is-invalid @enderror"
                                        value="{{ old('kategori', $keuangan->kategori) }}"
                                        placeholder="Contoh: Operasional, Donasi, Gaji..." maxlength="50"
                                        list="kategori-list">
                                    <datalist id="kategori-list">
                                        <option value="Operasional">
                                        <option value="Donasi Uang">
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
                                    <label class="form-label">Nominal <span class="required-mark">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="nominal" id="nominal"
                                            class="form-control @error('nominal') is-invalid @enderror"
                                            value="{{ old('nominal', $keuangan->nominal) }}"
                                            placeholder="0" min="1" required>
                                    </div>
                                    @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Keterangan --}}
                                <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <input type="text" name="keterangan"
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        value="{{ old('keterangan', $keuangan->keterangan) }}"
                                        placeholder="Deskripsi singkat transaksi..." maxlength="255">
                                    @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                            </div>
                        </div>
                    </div>

                </div>{{-- end col-lg-8 --}}

                {{-- Kolom Kanan --}}
                <div class="col-lg-4">

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="section-divider"><i class="fas fa-file-image me-2"></i>Bukti Transaksi</div>

                            {{-- Bukti existing --}}
                            @if($keuangan->bukti)
                            <div class="mb-3">
                                <p class="text-muted small mb-1">Bukti saat ini:</p>
                                <img src="{{ asset('storage/' . $keuangan->bukti) }}"
                                     alt="Bukti" class="bukti-existing">
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox" name="hapus_bukti" id="hapus_bukti" value="1">
                                    <label class="form-check-label text-danger small" for="hapus_bukti">
                                        Hapus bukti ini
                                    </label>
                                </div>
                            </div>
                            @endif

                            <p class="text-muted small mb-2">
                                {{ $keuangan->bukti ? 'Ganti dengan foto baru (opsional):' : 'Upload foto bukti (opsional):' }}
                            </p>

                            <input type="file" name="bukti" id="bukti-input"
                                class="form-control @error('bukti') is-invalid @enderror"
                                accept="image/jpg,image/jpeg,image/png,image/webp">
                            @error('bukti')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            <div class="bukti-preview" id="bukti-preview">
                                <img src="" alt="Preview bukti" id="bukti-img">
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-semibold" style="border-radius:10px;">
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
function setJenis(jenis) {
    document.getElementById('jenis').value = jenis;
    const btnP = document.getElementById('btn-pemasukan');
    const btnK = document.getElementById('btn-pengeluaran');
    const secDonasi = document.getElementById('section-donasi');

    btnP.className = 'jenis-btn' + (jenis === 'pemasukan'   ? ' active-pemasukan'   : '');
    btnK.className = 'jenis-btn' + (jenis === 'pengeluaran' ? ' active-pengeluaran' : '');

    secDonasi.style.display = jenis === 'pemasukan' ? '' : 'none';
    if (jenis === 'pengeluaran') {
        document.getElementById('donasi_id').value = '';
        document.getElementById('donasi-info').classList.remove('show');
    }
}

document.getElementById('donasi_id')?.addEventListener('change', function () {
    const opt     = this.options[this.selectedIndex];
    const nominal = opt.dataset.nominal;
    const info    = document.getElementById('donasi-info');

    if (this.value && nominal) {
        document.getElementById('nominal').value = nominal;
        info.classList.add('show');
    } else {
        info.classList.remove('show');
    }
});

@unless($isAdminPanti)
document.getElementById('panti_asuhan_id')?.addEventListener('change', function () {
    const pantiId = this.value;
    const sel     = document.getElementById('donasi_id');

    sel.innerHTML = '<option value="">-- Tidak dari donasi / pilih donasi --</option>';
    document.getElementById('donasi-info').classList.remove('show');

    if (!pantiId) return;

    fetch(`{{ route('keuangan.donasi-by-panti') }}?panti_asuhan_id=${pantiId}`)
        .then(r => r.json())
        .then(list => {
            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value           = d.id;
                opt.dataset.nominal = d.nominal;
                opt.textContent     = d.label;
                sel.appendChild(opt);
            });
        });
});
@endunless

document.getElementById('bukti-input').addEventListener('change', function () {
    const file    = this.files[0];
    const preview = document.getElementById('bukti-preview');
    const img     = document.getElementById('bukti-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; preview.classList.add('show'); };
        reader.readAsDataURL(file);
    } else {
        preview.classList.remove('show');
    }
});
</script>
@endsection

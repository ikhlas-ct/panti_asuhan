@extends('layouts.user.user')
@section('title', 'Tambah Donasi')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#1269db; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#e8f3ff; color:#1269db; }
    .ph-title { font-size:1.1rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .section-divider { background:#f8f9fa; border-left:4px solid #1269db; padding:9px 14px; border-radius:0 6px 6px 0; font-weight:700; font-size:.85rem; color:#1269db; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px; }
    label { font-size:.875rem; font-weight:600; color:#475569; margin-bottom:5px; }
    .required-mark { color:#dc3545; }
    .form-control,.form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.87rem; padding:9px 13px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus,.form-select:focus { border-color:#1269db; background:#fff; box-shadow:0 0 0 3px rgba(18,105,219,.12); }

    /* Jenis & Metode toggle */
    .toggle-btn { flex:1; padding:14px 8px; border:2px solid #e2e8f0; border-radius:12px; text-align:center; cursor:pointer; transition:all .2s; background:#f8fafc; color:#64748b; font-weight:700; font-size:.88rem; }
    .toggle-btn:hover { border-color:#1269db; background:#eff6ff; color:#1269db; }
    .toggle-btn.active { border-color:#1269db; background:#eff6ff; color:#1269db; }
    .toggle-btn i { font-size:1.4rem; display:block; margin-bottom:5px; }

    /* Upload */
    .upload-area { border:2px dashed #e2e8f0; border-radius:12px; padding:20px; text-align:center; cursor:pointer; background:#fafbfc; transition:border-color .2s,background .2s; }
    .upload-area:hover { border-color:#1269db; background:#eff6ff; }
    .upload-preview { max-width:100%; max-height:150px; border-radius:10px; border:2px solid #e2e8f0; margin-top:8px; display:none; }

    /* Barang rows */
    .barang-row { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:16px; margin-bottom:12px; position:relative; transition:border-color .2s; }
    .barang-row:hover { border-color:#1269db; }
    .barang-row-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .barang-row-num { background:#1269db; color:#fff; width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; }
    .btn-remove-row { background:#fee2e2; color:#dc2626; border:none; border-radius:8px; width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; transition:all .15s; }
    .btn-remove-row:hover { background:#dc2626; color:#fff; }

    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.87rem; padding:10px 24px; box-shadow:0 2px 8px rgba(26,115,232,.3); transition:all .2s; }
    .btn-primary:hover { transform:translateY(-1px); }
    .btn-outline-secondary { border-radius:10px; font-size:.87rem; border-color:#e2e8f0; color:#64748b; }
    .info-panel { border-radius:14px; padding:20px; }
    .info-panel.auto   { background:#f0fdf4; border:1.5px solid #bbf7d0; }
    .info-panel.pending { background:#fffbeb; border:1.5px solid #fde68a; }
    .btn-add-row { border-radius:10px; border:2px dashed #1269db; color:#1269db; background:transparent; font-weight:600; font-size:.83rem; padding:10px; width:100%; transition:all .2s; }
    .btn-add-row:hover { background:#eff6ff; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-plus-circle"></i></div>
            <div>
                <h5 class="ph-title">Tambah Donasi</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('donasi.index') }}">Donasi</a></li>
                    <li><span class="bc-active">Tambah</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('donasi.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <form action="{{ route('donasi.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Jenis Donasi --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-tag"></i> Jenis Donasi</div>
                        <div class="d-flex gap-3 mb-3">
                            <div class="toggle-btn active" id="btn-uang" onclick="setJenis('uang')">
                                <i class="fas fa-money-bill-wave text-success"></i> Donasi Uang
                            </div>
                            <div class="toggle-btn" id="btn-barang" onclick="setJenis('barang')">
                                <i class="fas fa-box-open text-primary"></i> Donasi Barang
                            </div>
                        </div>
                        <input type="hidden" name="jenis_donasi" id="jenis_donasi" value="{{ old('jenis_donasi','uang') }}">

                        {{-- Metode --}}
                        <div class="section-divider mt-3"><i class="fas fa-truck"></i> Metode</div>
                        <div class="d-flex gap-3">
                            <div class="toggle-btn active" id="btn-online" onclick="setMetode('online')">
                                <i class="fas fa-wifi text-purple" style="color:#7c3aed;"></i> Online
                                <div style="font-size:.72rem;color:#64748b;margin-top:3px;">Transfer / QRIS</div>
                            </div>
                            <div class="toggle-btn" id="btn-kunjungan" onclick="setMetode('kunjungan')">
                                <i class="fas fa-walking" style="color:#c2410c;"></i> Kunjungan
                                <div style="font-size:.72rem;color:#64748b;margin-top:3px;">Datang Langsung</div>
                            </div>
                        </div>
                        <input type="hidden" name="metode" id="metode" value="{{ old('metode','online') }}">
                        @error('jenis_donasi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        @error('metode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Donatur & Panti --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-users"></i> Donatur & Tujuan</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Donatur <span class="required-mark">*</span></label>
                                <select name="donatur_id" class="form-select @error('donatur_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Donatur --</option>
                                    @foreach($donaturList as $d)
                                        <option value="{{ $d->id }}" {{ old('donatur_id')==$d->id?'selected':'' }}>
                                            {{ $d->nama }} ({{ ucfirst($d->jenis_donatur) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('donatur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Panti Asuhan Tujuan <span class="required-mark">*</span></label>
                                <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Panti --</option>
                                    @foreach($pantis as $p)
                                        <option value="{{ $p->id }}" {{ old('panti_asuhan_id')==$p->id?'selected':'' }}>{{ $p->nama_panti }}</option>
                                    @endforeach
                                </select>
                                @error('panti_asuhan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Donasi <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal_donasi"
                                    class="form-control @error('tanggal_donasi') is-invalid @enderror"
                                    value="{{ old('tanggal_donasi', date('Y-m-d')) }}" required>
                                @error('tanggal_donasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Tanggal kunjungan (tampil jika metode kunjungan) --}}
                            <div class="col-md-6" id="field-kunjungan" style="display:none;">
                                <label class="form-label">Tanggal Kunjungan</label>
                                <input type="date" name="tanggal_kunjungan"
                                    class="form-control" value="{{ old('tanggal_kunjungan') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION UANG --}}
                <div id="section-uang" class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-money-bill-wave"></i> Detail Donasi Uang</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nominal <span class="required-mark">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:10px 0 0 10px;font-weight:700;">Rp</span>
                                    <input type="number" name="nominal"
                                        class="form-control @error('nominal') is-invalid @enderror"
                                        style="border-left:none;border-radius:0 10px 10px 0;"
                                        value="{{ old('nominal') }}" placeholder="100000" min="1000">
                                    @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">
                                    Bukti Transfer / Pembayaran
                                    <span id="label-bukti-wajib" class="required-mark">*</span>
                                    <span id="label-bukti-opsional" class="text-muted" style="font-size:.8rem;display:none;">(opsional untuk kunjungan)</span>
                                </label>
                                <div class="upload-area" onclick="document.getElementById('bukti-input').click()">
                                    <i class="fas fa-cloud-upload-alt text-muted" style="font-size:1.8rem;display:block;margin-bottom:6px;"></i>
                                    <div class="text-muted" style="font-size:.83rem;">Klik untuk upload bukti transfer / QRIS</div>
                                    <div class="text-muted" style="font-size:.74rem;">JPG / PNG / WEBP – maks. 2 MB</div>
                                </div>
                                <input type="file" id="bukti-input" name="bukti_transfer" accept="image/*" class="d-none">
                                <img id="bukti-preview" class="upload-preview">
                                @error('bukti_transfer')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION BARANG --}}
                <div id="section-barang" class="card shadow-sm border-0 mb-4" style="display:none!important;">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-boxes"></i> Daftar Barang Donasi</div>

                        @error('barang')
                            <div class="alert" style="background:#fee2e2;color:#991b1b;border-radius:10px;padding:10px 14px;font-size:.83rem;margin-bottom:12px;">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Container item barang --}}
                        <div id="barang-container">
                            {{-- Row 1 default --}}
                            <div class="barang-row" data-index="0">
                                <div class="barang-row-header">
                                    <span class="barang-row-num">1</span>
                                    <button type="button" class="btn-remove-row" onclick="removeBarangRow(this)" title="Hapus item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <label class="form-label">Nama Barang <span class="required-mark">*</span></label>
                                        <input type="text" name="barang[0][nama_barang]"
                                            class="form-control @error('barang.0.nama_barang') is-invalid @enderror"
                                            value="{{ old('barang.0.nama_barang') }}" placeholder="Contoh: Beras">
                                        @error('barang.0.nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah <span class="required-mark">*</span></label>
                                        <input type="number" name="barang[0][jumlah_barang]"
                                            class="form-control @error('barang.0.jumlah_barang') is-invalid @enderror"
                                            value="{{ old('barang.0.jumlah_barang') }}" placeholder="10" min="1">
                                        @error('barang.0.jumlah_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="barang[0][satuan_barang]"
                                            class="form-control" value="{{ old('barang.0.satuan_barang') }}" placeholder="kg">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Foto</label>
                                        <input type="file" name="barang[0][foto_barang]" accept="image/*"
                                            class="form-control" style="padding:6px;">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" name="barang[0][keterangan]"
                                            class="form-control" value="{{ old('barang.0.keterangan') }}"
                                            placeholder="Kondisi, merek, catatan...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn-add-row mt-2" onclick="addBarangRow()">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Item Barang
                        </button>

                        <div class="mt-3">
                            <label class="form-label">Deskripsi Umum Barang</label>
                            <textarea name="deskripsi_barang" rows="2" class="form-control"
                                placeholder="Keterangan umum tentang barang yang didonasikan...">{{ old('deskripsi_barang') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                {{-- Info panel role --}}
@if(in_array(auth()->user()->role, ['admin_dinsos','admin_panti']))
                    <div class="info-panel auto mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-bolt text-success fs-5"></i>
                            <span class="fw-bold" style="color:#166534;">Langsung Diterima</span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.75;">
                            Sebagai <strong>{{ auth()->user()->role === 'admin_dinsos' ? 'Admin Dinsos' : 'Admin Panti' }}</strong>,
                            donasi langsung diterima. Jika jenis <strong>Uang</strong>, otomatis tercatat sebagai
                            <strong>pemasukan keuangan</strong>.
                        </p>
                    </div>
                @else
                    <div class="info-panel pending mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-clock text-warning fs-5"></i>
                            <span class="fw-bold" style="color:#92400e;">Menunggu Verifikasi</span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.75;">
                            Donasi Anda akan berstatus <strong>Pending</strong> dan perlu diverifikasi oleh
                            <strong>Admin Dinsos</strong> atau <strong>Admin Panti</strong>.
                        </p>
                    </div>
                @endif

                {{-- Catatan --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-sticky-note"></i> Catatan</div>
                        <textarea name="catatan" rows="4" class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Pesan atau catatan dari donatur...">{{ old('catatan') }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Simpan Donasi
                    </button>
                    <a href="{{ route('donasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    let barangIndex = 1;

    function setJenis(jenis) {
        document.getElementById('jenis_donasi').value = jenis;
        document.getElementById('btn-uang').classList.toggle('active', jenis === 'uang');
        document.getElementById('btn-barang').classList.toggle('active', jenis === 'barang');
        document.getElementById('section-uang').style.setProperty('display', jenis === 'uang' ? '' : 'none', 'important');
        document.getElementById('section-barang').style.setProperty('display', jenis === 'barang' ? '' : 'none', 'important');
    }

    function setMetode(metode) {
        document.getElementById('metode').value = metode;
        document.getElementById('btn-online').classList.toggle('active', metode === 'online');
        document.getElementById('btn-kunjungan').classList.toggle('active', metode === 'kunjungan');
        document.getElementById('field-kunjungan').style.display = metode === 'kunjungan' ? '' : 'none';
        // Bukti wajib hanya untuk online
        document.getElementById('label-bukti-wajib').style.display = metode === 'online' ? '' : 'none';
        document.getElementById('label-bukti-opsional').style.display = metode === 'kunjungan' ? '' : 'none';
    }

    function addBarangRow() {
        const idx = barangIndex++;
        const tpl = `
        <div class="barang-row" data-index="${idx}">
            <div class="barang-row-header">
                <span class="barang-row-num">${document.querySelectorAll('.barang-row').length + 1}</span>
                <button type="button" class="btn-remove-row" onclick="removeBarangRow(this)" title="Hapus">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-5">
                    <label class="form-label">Nama Barang <span class="required-mark">*</span></label>
                    <input type="text" name="barang[${idx}][nama_barang]" class="form-control" placeholder="Nama barang" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah <span class="required-mark">*</span></label>
                    <input type="number" name="barang[${idx}][jumlah_barang]" class="form-control" placeholder="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="barang[${idx}][satuan_barang]" class="form-control" placeholder="kg">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Foto</label>
                    <input type="file" name="barang[${idx}][foto_barang]" accept="image/*" class="form-control" style="padding:6px;">
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="barang[${idx}][keterangan]" class="form-control" placeholder="Kondisi, merek...">
                </div>
            </div>
        </div>`;
        document.getElementById('barang-container').insertAdjacentHTML('beforeend', tpl);
        reNumberRows();
    }

    function removeBarangRow(btn) {
        const rows = document.querySelectorAll('.barang-row');
        if (rows.length <= 1) { alert('Minimal harus ada 1 item barang.'); return; }
        btn.closest('.barang-row').remove();
        reNumberRows();
    }

    function reNumberRows() {
        document.querySelectorAll('.barang-row').forEach((row, i) => {
            row.querySelector('.barang-row-num').textContent = i + 1;
        });
    }

    // Init
    setJenis('{{ old('jenis_donasi','uang') }}');
    setMetode('{{ old('metode','online') }}');

    // Preview bukti
    document.getElementById('bukti-input').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { const img = document.getElementById('bukti-preview'); img.src = e.target.result; img.style.display = 'block'; };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection

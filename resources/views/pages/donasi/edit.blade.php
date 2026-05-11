@extends('layouts.user.user')
@section('title', 'Edit Donasi #' . $donasi->id)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#ca8a04; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#fefce8; color:#ca8a04; }
    .ph-title { font-size:1.1rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .section-divider { background:#f8f9fa; border-left:4px solid #ca8a04; padding:9px 14px; border-radius:0 6px 6px 0; font-weight:700; font-size:.85rem; color:#ca8a04; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px; }
    label { font-size:.875rem; font-weight:600; color:#475569; margin-bottom:5px; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.87rem; padding:9px 13px; color:#334155; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:#ca8a04; background:#fff; box-shadow:0 0 0 3px rgba(202,138,4,.12); }

    .toggle-btn { flex:1; padding:14px 8px; border:2px solid #e2e8f0; border-radius:12px; text-align:center; cursor:pointer; transition:all .2s; background:#f8fafc; color:#64748b; font-weight:700; font-size:.88rem; }
    .toggle-btn:hover { border-color:#ca8a04; background:#fefce8; color:#ca8a04; }
    .toggle-btn.active { border-color:#ca8a04; background:#fefce8; color:#ca8a04; }
    .toggle-btn i { font-size:1.4rem; display:block; margin-bottom:5px; }

    .upload-area { border:2px dashed #e2e8f0; border-radius:12px; padding:18px; text-align:center; cursor:pointer; background:#fafbfc; transition:border-color .2s,background .2s; }
    .upload-area:hover { border-color:#ca8a04; background:#fefce8; }
    .upload-preview { max-width:100%; max-height:150px; border-radius:10px; border:2px solid #e2e8f0; margin-top:8px; display:none; }
    .existing-foto { width:60px; height:60px; object-fit:cover; border-radius:8px; border:2px solid #e2e8f0; }

    .barang-row { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:16px; margin-bottom:12px; position:relative; transition:border-color .2s; }
    .barang-row:hover { border-color:#ca8a04; }
    .barang-row-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; }
    .barang-row-num { background:#ca8a04; color:#fff; width:24px; height:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; }
    .btn-remove-row { background:#fee2e2; color:#dc2626; border:none; border-radius:8px; width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; transition:all .15s; }
    .btn-remove-row:hover { background:#dc2626; color:#fff; }
    .btn-add-row { border-radius:10px; border:2px dashed #ca8a04; color:#ca8a04; background:transparent; font-weight:600; font-size:.83rem; padding:10px; width:100%; transition:all .2s; }
    .btn-add-row:hover { background:#fefce8; }

    .btn-warning { background:linear-gradient(135deg,#ca8a04,#a16207); border:none; border-radius:10px; font-weight:600; font-size:.87rem; padding:10px 24px; color:#fff; box-shadow:0 2px 8px rgba(202,138,4,.3); transition:all .2s; }
    .btn-warning:hover { transform:translateY(-1px); color:#fff; }
    .btn-outline-secondary { border-radius:10px; font-size:.87rem; border-color:#e2e8f0; color:#64748b; }
    .alert { border-radius:12px; border:none; font-size:.84rem; padding:12px 16px; }
    .alert-warning { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }

    /* Info donatur/panti (untuk role terbatas) */
    .field-readonly-info { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:10px 14px; display:flex; align-items:center; gap:10px; font-size:.87rem; color:#15803d; font-weight:600; }
    .field-readonly-info i { font-size:1rem; flex-shrink:0; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <h5 class="ph-title">Edit Donasi #{{ $donasi->id }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('donasi.index') }}">Donasi</a></li>
                    <li><a href="{{ route('donasi.show', $donasi) }}">Detail</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('donasi.show', $donasi) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <span>Hanya donasi berstatus <strong>Pending</strong> yang dapat diedit. Perubahan item barang akan menggantikan semua item lama.</span>
    </div>

    <form action="{{ route('donasi.update', $donasi) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf @method('PUT')

        <div class="row g-4">

            {{-- ── Kolom Kiri ── --}}
            <div class="col-lg-8">

                {{-- Jenis & Metode --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-tag"></i> Jenis Donasi</div>
                        <div class="d-flex gap-3 mb-4">
                            <div class="toggle-btn {{ old('jenis_donasi', $donasi->jenis_donasi) === 'uang'   ? 'active' : '' }}"
                                 id="btn-uang" onclick="setJenis('uang')">
                                <i class="fas fa-money-bill-wave text-success"></i> Donasi Uang
                            </div>
                            <div class="toggle-btn {{ old('jenis_donasi', $donasi->jenis_donasi) === 'barang' ? 'active' : '' }}"
                                 id="btn-barang" onclick="setJenis('barang')">
                                <i class="fas fa-box-open text-primary"></i> Donasi Barang
                            </div>
                        </div>
                        <input type="hidden" name="jenis_donasi" id="jenis_donasi"
                               value="{{ old('jenis_donasi', $donasi->jenis_donasi) }}">

                        <div class="section-divider"><i class="fas fa-truck"></i> Metode</div>
                        <div class="d-flex gap-3">
                            <div class="toggle-btn {{ old('metode', $donasi->metode) === 'online'    ? 'active' : '' }}"
                                 id="btn-online" onclick="setMetode('online')">
                                <i class="fas fa-wifi" style="color:#7c3aed;"></i> Online
                                <div style="font-size:.72rem;color:#64748b;margin-top:3px;">Transfer / QRIS</div>
                            </div>
                            <div class="toggle-btn {{ old('metode', $donasi->metode) === 'kunjungan' ? 'active' : '' }}"
                                 id="btn-kunjungan" onclick="setMetode('kunjungan')">
                                <i class="fas fa-walking" style="color:#c2410c;"></i> Kunjungan
                                <div style="font-size:.72rem;color:#64748b;margin-top:3px;">Datang Langsung</div>
                            </div>
                        </div>
                        <input type="hidden" name="metode" id="metode"
                               value="{{ old('metode', $donasi->metode) }}">

                        @error('jenis_donasi')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        @error('metode')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Donatur & Panti --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-users"></i> Donatur & Tujuan</div>
                        <div class="row g-3">

                            {{-- ── DONATUR ── --}}
                            <div class="col-md-6">
                                <label class="form-label">Donatur <span class="required-mark">*</span></label>

                                @if(auth()->user()->role === 'donatur')
                                    {{-- Donatur: tidak bisa ganti, tampilkan info saja --}}
                                    <div class="field-readonly-info">
                                        <i class="fas fa-user-check"></i>
                                        {{ $donasi->donatur?->nama }}
                                        <span class="text-muted fw-normal ms-1" style="font-size:.8rem;">({{ ucfirst($donasi->donatur?->jenis_donatur) }})</span>
                                    </div>
                                    <input type="hidden" name="donatur_id" value="{{ $donasi->donatur_id }}">
                                    @error('donatur_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                                @elseif(auth()->user()->role === 'admin_panti')
                                    {{-- Admin panti: bisa ganti donatur tapi tidak bisa ganti panti --}}
                                    <select name="donatur_id" class="form-select @error('donatur_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Donatur --</option>
                                        @foreach($donaturList as $d)
                                            <option value="{{ $d->id }}"
                                                {{ old('donatur_id', $donasi->donatur_id) == $d->id ? 'selected' : '' }}>
                                                {{ $d->nama }} ({{ ucfirst($d->jenis_donatur) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('donatur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                @else
                                    {{-- Admin dinsos: bebas ganti donatur --}}
                                    <select name="donatur_id" class="form-select @error('donatur_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Donatur --</option>
                                        @foreach($donaturList as $d)
                                            <option value="{{ $d->id }}"
                                                {{ old('donatur_id', $donasi->donatur_id) == $d->id ? 'selected' : '' }}>
                                                {{ $d->nama }} ({{ ucfirst($d->jenis_donatur) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('donatur_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            {{-- ── PANTI ASUHAN ── --}}
                            <div class="col-md-6">
                                <label class="form-label">Panti Asuhan Tujuan <span class="required-mark">*</span></label>

                                @if(in_array(auth()->user()->role, ['donatur', 'admin_panti']))
                                    {{-- Donatur & Admin panti: tidak bisa ganti panti --}}
                                    <div class="field-readonly-info">
                                        <i class="fas fa-home"></i>
                                        {{ $donasi->pantiAsuhan?->nama_panti }}
                                    </div>
                                    <input type="hidden" name="panti_asuhan_id" value="{{ $donasi->panti_asuhan_id }}">
                                    @error('panti_asuhan_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                @else
                                    {{-- Admin dinsos: bebas ganti panti --}}
                                    <select name="panti_asuhan_id" class="form-select @error('panti_asuhan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Panti --</option>
                                        @foreach($pantis as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('panti_asuhan_id', $donasi->panti_asuhan_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('panti_asuhan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Donasi <span class="required-mark">*</span></label>
                                <input type="date" name="tanggal_donasi"
                                    class="form-control @error('tanggal_donasi') is-invalid @enderror"
                                    value="{{ old('tanggal_donasi', $donasi->tanggal_donasi?->format('Y-m-d')) }}" required>
                                @error('tanggal_donasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6" id="field-kunjungan"
                                 style="{{ old('metode', $donasi->metode) === 'kunjungan' ? '' : 'display:none;' }}">
                                <label class="form-label">Tanggal Kunjungan</label>
                                <input type="date" name="tanggal_kunjungan" class="form-control"
                                    value="{{ old('tanggal_kunjungan', $donasi->tanggal_kunjungan?->format('Y-m-d')) }}">
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
                                    <span class="input-group-text"
                                          style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;border-radius:10px 0 0 10px;font-weight:700;">Rp</span>
                                    <input type="number" name="nominal"
                                        class="form-control @error('nominal') is-invalid @enderror"
                                        style="border-left:none;border-radius:0 10px 10px 0;"
                                        value="{{ old('nominal', $donasi->nominal) }}" min="1000">
                                    @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Bukti Transfer / Pembayaran</label>

                                @if($donasi->bukti_transfer)
                                    <div class="d-flex align-items-center gap-3 mb-2 p-3"
                                         style="background:#f0fdf4;border-radius:10px;border:1px solid #bbf7d0;">
                                        <a href="{{ asset('storage/'.$donasi->bukti_transfer) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$donasi->bukti_transfer) }}"
                                                 class="existing-foto">
                                        </a>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.82rem;color:#15803d;">
                                                <i class="fas fa-check-circle me-1"></i>Foto sudah ada
                                            </div>
                                            <div class="text-muted" style="font-size:.75rem;">Upload baru untuk mengganti</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="upload-area" onclick="document.getElementById('bukti-input').click()">
                                    <i class="fas fa-cloud-upload-alt text-muted" style="font-size:1.6rem;display:block;margin-bottom:6px;"></i>
                                    <div class="text-muted" style="font-size:.82rem;">
                                        {{ $donasi->bukti_transfer ? 'Klik untuk ganti bukti (opsional)' : 'Klik untuk upload bukti' }}
                                    </div>
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
                <div id="section-barang" class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-boxes"></i> Daftar Barang Donasi</div>

                        @if($errors->has('barang'))
                            <div class="alert" style="background:#fee2e2;color:#991b1b;border-radius:10px;padding:10px 14px;font-size:.83rem;margin-bottom:12px;">
                                {{ $errors->first('barang') }}
                            </div>
                        @endif

                        <div id="barang-container">
                            @forelse($donasi->barang as $i => $b)
                            <div class="barang-row" data-index="{{ $i }}">
                                <div class="barang-row-header">
                                    <span class="barang-row-num">{{ $i + 1 }}</span>
                                    <button type="button" class="btn-remove-row" onclick="removeBarangRow(this)" title="Hapus item">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <label class="form-label">Nama Barang <span class="required-mark">*</span></label>
                                        <input type="text" name="barang[{{ $i }}][nama_barang]"
                                            class="form-control @error('barang.'.$i.'.nama_barang') is-invalid @enderror"
                                            value="{{ old('barang.'.$i.'.nama_barang', $b->nama_barang) }}"
                                            placeholder="Nama barang" required>
                                        @error('barang.'.$i.'.nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah <span class="required-mark">*</span></label>
                                        <input type="number" name="barang[{{ $i }}][jumlah_barang]"
                                            class="form-control @error('barang.'.$i.'.jumlah_barang') is-invalid @enderror"
                                            value="{{ old('barang.'.$i.'.jumlah_barang', $b->jumlah_barang) }}"
                                            min="1" required>
                                        @error('barang.'.$i.'.jumlah_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="barang[{{ $i }}][satuan_barang]"
                                            class="form-control"
                                            value="{{ old('barang.'.$i.'.satuan_barang', $b->satuan_barang) }}"
                                            placeholder="kg">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Foto Baru</label>
                                        <input type="file" name="barang[{{ $i }}][foto_barang]"
                                            accept="image/*" class="form-control" style="padding:6px;">
                                        @if($b->foto_barang)
                                            <div class="mt-1">
                                                <a href="{{ asset('storage/'.$b->foto_barang) }}" target="_blank">
                                                    <img src="{{ asset('storage/'.$b->foto_barang) }}"
                                                         style="width:40px;height:40px;object-fit:cover;border-radius:6px;border:2px solid #e2e8f0;">
                                                </a>
                                                <span class="text-muted" style="font-size:.72rem;"> foto lama</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" name="barang[{{ $i }}][keterangan]"
                                            class="form-control"
                                            value="{{ old('barang.'.$i.'.keterangan', $b->keterangan) }}"
                                            placeholder="Kondisi, merek, catatan...">
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="barang-row" data-index="0">
                                <div class="barang-row-header">
                                    <span class="barang-row-num">1</span>
                                    <button type="button" class="btn-remove-row" onclick="removeBarangRow(this)" title="Hapus">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <label class="form-label">Nama Barang <span class="required-mark">*</span></label>
                                        <input type="text" name="barang[0][nama_barang]" class="form-control" placeholder="Nama barang" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah <span class="required-mark">*</span></label>
                                        <input type="number" name="barang[0][jumlah_barang]" class="form-control" placeholder="1" min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="barang[0][satuan_barang]" class="form-control" placeholder="kg">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Foto</label>
                                        <input type="file" name="barang[0][foto_barang]" accept="image/*" class="form-control" style="padding:6px;">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Keterangan</label>
                                        <input type="text" name="barang[0][keterangan]" class="form-control" placeholder="Kondisi, merek...">
                                    </div>
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <button type="button" class="btn-add-row mt-2" onclick="addBarangRow()">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Item Barang
                        </button>

                        <div class="mt-3">
                            <label class="form-label">Deskripsi Umum Barang</label>
                            <textarea name="deskripsi_barang" rows="2" class="form-control"
                                placeholder="Keterangan umum...">{{ old('deskripsi_barang', $donasi->deskripsi_barang) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ── Kolom Kanan ── --}}
            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-info-circle text-warning"></i>
                            <span class="fw-bold" style="font-size:.88rem;color:#92400e;">Catatan Edit</span>
                        </div>
                        <ul class="mb-0 ps-3 text-muted" style="font-size:.8rem;line-height:2;">
                            <li>Hanya donasi <strong>Pending</strong> yang bisa diedit</li>
                            @if(auth()->user()->role === 'donatur')
                                <li>Donatur dan panti tujuan <strong>tidak dapat diubah</strong></li>
                            @elseif(auth()->user()->role === 'admin_panti')
                                <li>Panti tujuan <strong>tidak dapat diubah</strong></li>
                            @endif
                            <li>Jika ganti jenis dari Uang ke Barang, isi ulang semua item</li>
                            <li>Upload foto baru untuk mengganti foto lama</li>
                            <li>Item barang lama akan <strong>diganti seluruhnya</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4" style="border-radius:14px;">
                    <div class="card-body p-4">
                        <div class="section-divider"><i class="fas fa-sticky-note"></i> Catatan</div>
                        <textarea name="catatan" rows="4"
                            class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Pesan atau catatan dari donatur...">{{ old('catatan', $donasi->catatan) }}</textarea>
                        @error('catatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('donasi.show', $donasi) }}" class="btn btn-outline-secondary">Batal</a>
                </div>

            </div>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
    let barangIndex = {{ $donasi->barang->count() > 0 ? $donasi->barang->count() : 1 }};

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
    }

    function addBarangRow() {
        const idx = barangIndex++;
        const num = document.querySelectorAll('.barang-row').length + 1;
        const tpl = `
        <div class="barang-row" data-index="${idx}">
            <div class="barang-row-header">
                <span class="barang-row-num">${num}</span>
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

    setJenis('{{ old('jenis_donasi', $donasi->jenis_donasi) }}');
    setMetode('{{ old('metode', $donasi->metode) }}');

    document.getElementById('bukti-input').addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('bukti-preview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection

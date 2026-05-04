@extends('layouts.user.user')

@section('title', 'Cetak Laporan Keuangan')

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
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#0369a1; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#e0f2fe; color:#0369a1; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; margin-top:4px; list-style:none; padding:0; margin-bottom:0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    label { font-size:.875rem; font-weight:600; margin-bottom:5px; display:block; color:#334155; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
        padding:9px 12px; color:#334155; background:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color:#0369a1; background:#fff;
        box-shadow:0 0 0 3px rgba(3,105,161,.12); outline:none;
    }

    .preview-box {
        background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:12px;
        padding:16px 20px; font-size:.85rem; color:#0c4a6e;
        display:none; margin-top:6px;
    }
    .preview-box.show { display:block; }
    .preview-box .pv-nama { font-size:1rem; font-weight:700; color:#0369a1; margin-bottom:4px; }
    .preview-box .pv-item { color:#334155; font-size:.83rem; margin-top:2px; }

    .btn-print {
        background:linear-gradient(135deg,#0369a1,#075985); border:none;
        border-radius:10px; font-weight:700; font-size:.9rem; padding:10px 24px;
        color:#fff; box-shadow:0 2px 8px rgba(3,105,161,.35);
        transition:all .2s; width:100%;
    }
    .btn-print:hover { background:linear-gradient(135deg,#075985,#0c4a6e); transform:translateY(-1px); }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; }

    .tip-box {
        background:#fffbeb; border:1px solid #fde68a; border-radius:10px;
        padding:12px 16px; font-size:.8rem; color:#92400e;
    }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-print"></i></div>
            <div>
                <h5 class="ph-title">Cetak Laporan Keuangan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('keuangan.index') }}">Keuangan</a></li>
                    <li><span class="bc-active">Cetak Laporan</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-1" style="color:#0369a1;">
                            <i class="fas fa-file-alt me-2"></i>Pilih Panti & Periode
                        </h6>
                        <p class="text-muted mb-4" style="font-size:.82rem;">
                            Laporan akan dicetak sesuai panti dan periode yang dipilih.
                        </p>

                        <form action="{{ route('keuangan.laporan.cetak') }}" method="GET" target="_blank" id="form-laporan">
                            <div class="row g-3">

                                {{-- Panti --}}
                                @if($isAdminPanti)
                                    {{-- Admin panti: panti otomatis, tampilkan nama saja --}}
                                    <input type="hidden" name="panti_asuhan_id" value="{{ $pantiId }}">
                                    <div class="col-12">
                                        <label>Panti Asuhan</label>
                                        <div class="form-control" style="background:#f1f5f9; color:#64748b; cursor:not-allowed;">
                                            <i class="fas fa-building me-2 text-primary"></i>
                                            {{ $pantis->first()?->nama_panti ?? '-' }}
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <label>Panti Asuhan <span style="color:#dc3545;">*</span></label>
                                        <select name="panti_asuhan_id" id="sel-panti" class="form-select" required>
                                            <option value="">-- Pilih Panti Asuhan --</option>
                                            @foreach($pantis as $p)
                                                <option value="{{ $p->id }}"
                                                    data-alamat="{{ $p->alamat }}{{ $p->kecamatan ? ', ' . $p->kecamatan : '' }}"
                                                    data-kontak="{{ $p->nama_kontak ?? '-' }}">
                                                    {{ $p->nama_panti }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Preview info panti --}}
                                    <div class="col-12">
                                        <div class="preview-box" id="preview-panti">
                                            <div class="pv-nama" id="pv-nama"></div>
                                            <div class="pv-item"><i class="fas fa-map-marker-alt me-1 text-primary"></i><span id="pv-alamat"></span></div>
                                            <div class="pv-item"><i class="fas fa-user me-1 text-primary"></i>Kontak: <span id="pv-kontak"></span></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Bulan --}}
                                <div class="col-6">
                                    <label>Bulan</label>
                                    <select name="bulan" class="form-select">
                                        <option value="">Semua Bulan</option>
                                        @foreach(range(1,12) as $m)
                                            <option value="{{ $m }}">
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tahun --}}
                                <div class="col-6">
                                    <label>Tahun</label>
                                    <select name="tahun" class="form-select">
                                        <option value="">Semua Tahun</option>
                                        @foreach(range(date('Y'), date('Y')-5) as $y)
                                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tip --}}
                                <div class="col-12">
                                    <div class="tip-box">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Biarkan <strong>Bulan</strong> kosong untuk laporan setahun penuh.
                                        Biarkan keduanya kosong untuk semua periode.
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-print">
                                        <i class="fas fa-print me-2"></i> Buka & Cetak Laporan
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('sel-panti')?.addEventListener('change', function () {
    const opt     = this.options[this.selectedIndex];
    const preview = document.getElementById('preview-panti');

    if (this.value) {
        document.getElementById('pv-nama').textContent   = opt.text.trim();
        document.getElementById('pv-alamat').textContent = opt.dataset.alamat;
        document.getElementById('pv-kontak').textContent = opt.dataset.kontak;
        preview.classList.add('show');
    } else {
        preview.classList.remove('show');
    }
});
</script>
@endsection

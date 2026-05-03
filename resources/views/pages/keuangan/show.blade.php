@extends('layouts.user.user')

@section('title', 'Detail Transaksi Keuangan')

@section('styles')
<style>
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; }
    .ph-card.detail-page::before { background:#0369a1; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .ph-icon.detail { background:#e0f2fe; color:#0369a1; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .detail-label { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.4px; color:#94a3b8; margin-bottom:2px; }
    .detail-value { font-size:.9rem; color:#1e293b; font-weight:500; }

    .badge-pemasukan  { background:#dcfce7; color:#15803d; font-size:.78rem; padding:5px 12px; border-radius:7px; font-weight:700; }
    .badge-pengeluaran { background:#fee2e2; color:#dc2626; font-size:.78rem; padding:5px 12px; border-radius:7px; font-weight:700; }
    .badge-donasi     { background:#e0f2fe; color:#0369a1; font-size:.78rem; padding:5px 12px; border-radius:7px; font-weight:700; }

    .nominal-display {
        font-size:2rem; font-weight:800; letter-spacing:-.5px;
    }
    .nominal-display.pemasukan  { color:#15803d; }
    .nominal-display.pengeluaran { color:#dc2626; }

    .bukti-img { max-width:100%; border-radius:12px; border:2px solid #e2e8f0; }

    .info-row { display:flex; flex-direction:column; gap:2px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .info-row:last-child { border-bottom:none; }

    .donasi-box {
        background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card detail-page">
        <div class="ph-left">
            <div class="ph-icon detail"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Transaksi Keuangan</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('keuangan.index') }}">Keuangan</a></li>
                    <li><span class="bc-active">Detail</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('keuangan.edit', $keuangan) }}" class="btn btn-warning btn-sm fw-semibold">
                <i class="fas fa-pencil-alt me-1"></i> Edit
            </a>
            <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <div class="row g-4">

            {{-- Kolom Kiri: Info Utama --}}
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        {{-- Nominal besar di atas --}}
                        <div class="text-center mb-4 py-3" style="border-bottom:1px solid #f1f5f9;">
                            <div class="mb-1">
                                <span class="badge-{{ $keuangan->jenis }}">
                                    <i class="fas fa-arrow-circle-{{ $keuangan->jenis === 'pemasukan' ? 'down' : 'up' }} me-1"></i>
                                    {{ ucfirst($keuangan->jenis) }}
                                </span>
                            </div>
                            <div class="nominal-display {{ $keuangan->jenis }} mt-2">
                                {{ $keuangan->jenis === 'pengeluaran' ? '- ' : '+ ' }}Rp {{ number_format($keuangan->nominal, 0, ',', '.') }}
                            </div>
                            <div class="text-muted small mt-1">
                                {{ \Carbon\Carbon::parse($keuangan->tanggal)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>

                        {{-- Detail rows --}}
                        <div class="px-2">
                            @unless(Auth::user()->isAdminPanti())
                            <div class="info-row">
                                <div class="detail-label">Panti Asuhan</div>
                                <div class="detail-value">{{ $keuangan->pantiAsuhan->nama_panti ?? '-' }}</div>
                            </div>
                            @endunless

                            <div class="info-row">
                                <div class="detail-label">Kategori</div>
                                <div class="detail-value">{{ $keuangan->kategori ?? '-' }}</div>
                            </div>

                            <div class="info-row">
                                <div class="detail-label">Keterangan</div>
                                <div class="detail-value">{{ $keuangan->keterangan ?? '-' }}</div>
                            </div>

                            <div class="info-row">
                                <div class="detail-label">Dicatat pada</div>
                                <div class="detail-value">{{ $keuangan->created_at->translatedFormat('d F Y, H:i') }}</div>
                            </div>

                            @if($keuangan->updated_at != $keuangan->created_at)
                            <div class="info-row">
                                <div class="detail-label">Terakhir diperbarui</div>
                                <div class="detail-value">{{ $keuangan->updated_at->translatedFormat('d F Y, H:i') }}</div>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

                {{-- Info Donasi terkait --}}
                @if($keuangan->donasi)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-hand-holding-heart me-2"></i>Sumber: Donasi</h6>
                        <div class="donasi-box">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="detail-label">Donatur</div>
                                    <div class="detail-value">{{ $keuangan->donasi->donatur->nama ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Tanggal Donasi</div>
                                    <div class="detail-value">
                                        {{ \Carbon\Carbon::parse($keuangan->donasi->tanggal_donasi)->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Metode</div>
                                    <div class="detail-value">{{ ucfirst($keuangan->donasi->metode) }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-label">Nominal Donasi</div>
                                    <div class="detail-value fw-bold text-success">
                                        Rp {{ number_format($keuangan->donasi->nominal, 0, ',', '.') }}
                                    </div>
                                </div>
                                @if($keuangan->donasi->catatan)
                                <div class="col-12">
                                    <div class="detail-label">Catatan Donatur</div>
                                    <div class="detail-value">{{ $keuangan->donasi->catatan }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>{{-- end col-lg-8 --}}

            {{-- Kolom Kanan: Bukti --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-file-image me-2 text-primary"></i>Bukti Transaksi</h6>
                        @if($keuangan->bukti)
                            <a href="{{ asset('storage/' . $keuangan->bukti) }}" target="_blank">
                                <img src="{{ asset('storage/' . $keuangan->bukti) }}"
                                     alt="Bukti transaksi" class="bukti-img">
                            </a>
                            <div class="text-muted small mt-2 text-center">Klik gambar untuk buka di tab baru</div>
                        @else
                            <div class="text-center py-4">
                                <div style="width:60px;height:60px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                                    <i class="fas fa-file-image text-muted" style="font-size:1.5rem;"></i>
                                </div>
                                <div class="text-muted small">Tidak ada bukti transaksi</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Aksi</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('keuangan.edit', $keuangan) }}" class="btn btn-warning btn-sm fw-semibold" style="border-radius:10px;">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Transaksi
                            </a>
                            <button class="btn btn-danger btn-sm fw-semibold" id="btn-hapus" style="border-radius:10px;"
                                data-id="{{ $keuangan->id }}"
                                data-nominal="Rp {{ number_format($keuangan->nominal, 0, ',', '.') }}">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Transaksi
                            </button>
                            <form id="form-hapus-{{ $keuangan->id }}"
                                action="{{ route('keuangan.destroy', $keuangan) }}"
                                method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>{{-- end col-lg-4 --}}

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btn-hapus')?.addEventListener('click', function () {
    const id      = this.dataset.id;
    const nominal = this.dataset.nominal;
    swal({
        title: 'Hapus Transaksi?',
        text: `Data transaksi senilai "${nominal}" akan dihapus permanen.`,
        icon: 'warning',
        buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
        dangerMode: true,
    }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
});
</script>
@endsection

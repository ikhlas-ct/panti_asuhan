{{--
    Partial: _filter-periode.blade.php
    Pakai: @include('pages.laporan._filter-periode')
    Requires: $bulanList, $tahunList, $startCarbon, $endCarbon di view parent
--}}
<div class="card border-0 shadow-sm mb-4 laporan-filter-card">
    <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
        <i class="bi bi-funnel-fill text-primary"></i>
        <span class="fw-semibold text-dark">Filter Periode &amp; Pencarian</span>
        @if(request()->hasAny(['start_day','end_day','status','jenis_donasi','jenis','panti_asuhan_id']))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">

            {{-- ── PERIODE ─────────────────────────────────── --}}
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="form-label fw-semibold text-muted small text-uppercase ls-1">
                        <i class="bi bi-calendar-range me-1"></i>Periode Tanggal
                    </label>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <div class="small fw-semibold text-muted mb-2">
                            <i class="bi bi-calendar-check text-success me-1"></i>Tanggal Mulai
                        </div>
                        <div class="row g-2">
                            <div class="col-3">
                                <select name="start_day" class="form-select form-select-sm">
                                    <option value="">Hari</option>
                                    @for($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}"
                                            {{ $startCarbon->day == $d ? 'selected' : '' }}>
                                            {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-5">
                                <select name="start_month" class="form-select form-select-sm">
                                    <option value="">Bulan</option>
                                    @foreach($bulanList as $num => $nama)
                                        <option value="{{ $num }}"
                                            {{ $startCarbon->month == $num ? 'selected' : '' }}>
                                            {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="start_year" class="form-select form-select-sm">
                                    <option value="">Tahun</option>
                                    @foreach($tahunList as $y)
                                        <option value="{{ $y }}"
                                            {{ $startCarbon->year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <div class="small fw-semibold text-muted mb-2">
                            <i class="bi bi-calendar-x text-danger me-1"></i>Tanggal Selesai
                        </div>
                        <div class="row g-2">
                            <div class="col-3">
                                <select name="end_day" class="form-select form-select-sm">
                                    <option value="">Hari</option>
                                    @for($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}"
                                            {{ $endCarbon->day == $d ? 'selected' : '' }}>
                                            {{ str_pad($d, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-5">
                                <select name="end_month" class="form-select form-select-sm">
                                    <option value="">Bulan</option>
                                    @foreach($bulanList as $num => $nama)
                                        <option value="{{ $num }}"
                                            {{ $endCarbon->month == $num ? 'selected' : '' }}>
                                            {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="end_year" class="form-select form-select-sm">
                                    <option value="">Tahun</option>
                                    @foreach($tahunList as $y)
                                        <option value="{{ $y }}"
                                            {{ $endCarbon->year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── FILTER TAMBAHAN (inject per-halaman) ─────── --}}
            @if(isset($filterTambahan))
                <div class="row g-3 mb-3">
                    {!! $filterTambahan !!}
                </div>
            @endif

            <div class="d-flex gap-2 mt-1">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-search me-1"></i>Terapkan Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

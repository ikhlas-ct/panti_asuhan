<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Keuangan;
use App\Models\PantiAsuhan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // ── Helper: bangun date string dari part day/month/year ──────
    private function buildDate(Request $request, string $prefix, string $default): string
    {
        $day   = $request->input("{$prefix}_day");
        $month = $request->input("{$prefix}_month");
        $year  = $request->input("{$prefix}_year");

        if ($day && $month && $year) {
            try {
                return Carbon::createFromDate((int)$year, (int)$month, (int)$day)
                    ->format('Y-m-d');
            } catch (\Exception $e) {
                return $default;
            }
        }
        return $default;
    }

    // ── Helper: array bulan Indonesia ────────────────────────────
    private function bulanList(): array
    {
        return [
            1  => 'Januari',   2  => 'Februari', 3  => 'Maret',
            4  => 'April',     5  => 'Mei',       6  => 'Juni',
            7  => 'Juli',      8  => 'Agustus',   9  => 'September',
            10 => 'Oktober',   11 => 'November',  12 => 'Desember',
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // 1. RIWAYAT DONASI — untuk Donatur
    // ─────────────────────────────────────────────────────────────
    public function riwayatDonasi(Request $request)
    {
        $user    = Auth::user();
        $donatur = $user->donatur;

        if (!$donatur) {
            abort(403, 'Profil donatur tidak ditemukan. Hubungi administrator.');
        }

        $startDate = $this->buildDate($request, 'start', now()->startOfMonth()->toDateString());
        $endDate   = $this->buildDate($request, 'end',   now()->endOfMonth()->toDateString());

        $startCarbon = Carbon::parse($startDate);
        $endCarbon   = Carbon::parse($endDate);

        $query = Donasi::with(['pantiAsuhan', 'barang'])
            ->where('donatur_id', $donatur->id)
            ->whereBetween('tanggal_donasi', [$startDate, $endDate])
            ->orderBy('tanggal_donasi', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_donasi')) {
            $query->where('jenis_donasi', $request->jenis_donasi);
        }

        $donasi = $query->get();

        $summary = [
            'total_uang_diterima'   => $donasi->where('jenis_donasi', 'uang')
                                               ->where('status', 'diterima')
                                               ->sum('nominal'),
            'jml_barang_diterima'   => $donasi->where('jenis_donasi', 'barang')
                                               ->where('status', 'diterima')
                                               ->count(),
            'jml_pending'           => $donasi->where('status', 'pending')->count(),
            'jml_ditolak'           => $donasi->where('status', 'ditolak')->count(),
            'total_semua'           => $donasi->count(),
        ];

        return view('pages.laporan.riwayat-donasi', [
            'donasi'       => $donasi,
            'summary'      => $summary,
            'startCarbon'  => $startCarbon,
            'endCarbon'    => $endCarbon,
            'donatur'      => $donatur,
            'bulanList'    => $this->bulanList(),
            'tahunList'    => range(2024, (int)date('Y')),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // 2. LAPORAN KEUANGAN — admin_panti dan admin_dinsos
    // ─────────────────────────────────────────────────────────────
    public function laporanKeuangan(Request $request)
    {
        $user = Auth::user();

        $startDate = $this->buildDate($request, 'start', now()->startOfMonth()->toDateString());
        $endDate   = $this->buildDate($request, 'end',   now()->endOfMonth()->toDateString());

        $startCarbon = Carbon::parse($startDate);
        $endCarbon   = Carbon::parse($endDate);

        // Tentukan panti_asuhan_id yang dipakai
        $pantis          = collect();
        $selectedPantiId = $request->input('panti_asuhan_id');
        $selectedPanti   = null;

        if ($user->isAdminPanti()) {
            // Hanya panti miliknya
            $selectedPantiId = optional($user->pengurus)->panti_asuhan_id;
            if (!$selectedPantiId) {
                abort(403, 'Pengurus tidak terhubung ke panti asuhan mana pun.');
            }
            $selectedPanti = PantiAsuhan::find($selectedPantiId);
        } else {
            // admin_dinsos: bisa pilih semua panti
            $pantis = PantiAsuhan::aktif()->orderBy('nama_panti')->get();
            if ($selectedPantiId) {
                $selectedPanti = PantiAsuhan::find($selectedPantiId);
            }
        }

        $query = Keuangan::with(['pantiAsuhan', 'donasi.donatur'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc');

        if ($selectedPantiId) {
            $query->where('panti_asuhan_id', $selectedPantiId);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $keuangan = $query->get();

        $summary = [
            'total_pemasukan'   => $keuangan->where('jenis', 'pemasukan')->sum('nominal'),
            'total_pengeluaran' => $keuangan->where('jenis', 'pengeluaran')->sum('nominal'),
            'saldo'             => $keuangan->where('jenis', 'pemasukan')->sum('nominal')
                                   - $keuangan->where('jenis', 'pengeluaran')->sum('nominal'),
        ];

        return view('pages.laporan.keuangan', [
            'keuangan'        => $keuangan,
            'summary'         => $summary,
            'startCarbon'     => $startCarbon,
            'endCarbon'       => $endCarbon,
            'pantis'          => $pantis,
            'selectedPanti'   => $selectedPanti,
            'selectedPantiId' => $selectedPantiId,
            'bulanList'       => $this->bulanList(),
            'tahunList'       => range(2024, (int)date('Y')),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // 3. LAPORAN DONASI PER PANTI — admin_dinsos only
    // ─────────────────────────────────────────────────────────────
    public function laporanDonasiPerPanti(Request $request)
    {
        $startDate = $this->buildDate($request, 'start', now()->startOfMonth()->toDateString());
        $endDate   = $this->buildDate($request, 'end',   now()->endOfMonth()->toDateString());

        $startCarbon = Carbon::parse($startDate);
        $endCarbon   = Carbon::parse($endDate);

        $filterStatus = $request->input('status');

        $pantis = PantiAsuhan::with(['donasi' => function ($q) use ($startDate, $endDate, $filterStatus) {
            $q->whereBetween('tanggal_donasi', [$startDate, $endDate]);
            if ($filterStatus) {
                $q->where('status', $filterStatus);
            }
        }])
        ->aktif()
        ->orderBy('nama_panti')
        ->get();

        $data = $pantis->map(function ($panti) {
            $d = $panti->donasi;
            $uangDiterima = $d->where('jenis_donasi', 'uang')->where('status', 'diterima');

            return [
                'panti'             => $panti,
                'jml_donasi_uang'   => $d->where('jenis_donasi', 'uang')->count(),
                'total_nominal'     => $uangDiterima->sum('nominal'),
                'jml_donasi_barang' => $d->where('jenis_donasi', 'barang')->count(),
                'total_donasi'      => $d->count(),
                'jml_pending'       => $d->where('status', 'pending')->count(),
                'jml_diterima'      => $d->where('status', 'diterima')->count(),
                'jml_ditolak'       => $d->where('status', 'ditolak')->count(),
            ];
        });

        $grandTotal = [
            'jml_donasi_uang'   => $data->sum('jml_donasi_uang'),
            'total_nominal'     => $data->sum('total_nominal'),
            'jml_donasi_barang' => $data->sum('jml_donasi_barang'),
            'total_donasi'      => $data->sum('total_donasi'),
            'jml_pending'       => $data->sum('jml_pending'),
            'jml_diterima'      => $data->sum('jml_diterima'),
            'jml_ditolak'       => $data->sum('jml_ditolak'),
        ];

        return view('pages.laporan.donasi-per-panti', [
            'data'         => $data,
            'grandTotal'   => $grandTotal,
            'startCarbon'  => $startCarbon,
            'endCarbon'    => $endCarbon,
            'bulanList'    => $this->bulanList(),
            'tahunList'    => range(2024, (int)date('Y')),
        ]);
    }
}

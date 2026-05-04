<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Keuangan;
use App\Models\PantiAsuhan;
use App\Models\Pegawai;
use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dinsosDashboard()
    {
        // ── STAT UTAMA ────────────────────────────────────────────────
        $stats = [
            'total_panti'        => PantiAsuhan::count(),
            'panti_aktif'        => PantiAsuhan::aktif()->count(),
            'total_donatur'      => Donatur::count(),
            'donatur_aktif'      => Donatur::aktif()->count(),
            'total_donasi'       => Donasi::count(),
            'donasi_pending'     => Donasi::pending()->count(),
            'donasi_diterima'    => Donasi::diterima()->count(),
            'total_pegawai'      => Pegawai::count(),
            'total_pengurus'     => Pengurus::count(),

            // Keuangan
            'total_pemasukan'    => Keuangan::where('jenis', 'pemasukan')->sum('nominal'),
            'total_pengeluaran'  => Keuangan::where('jenis', 'pengeluaran')->sum('nominal'),
            'total_donasi_uang'  => Donasi::diterima()->uang()->sum('nominal'),
        ];

        $stats['saldo'] = $stats['total_pemasukan'] - $stats['total_pengeluaran'];

        // ── DONASI TERBARU ────────────────────────────────────────────
        $donasi_terbaru = Donasi::with(['donatur', 'pantiAsuhan'])
            ->latest()
            ->take(8)
            ->get();

        // ── DONASI PENDING (perlu verifikasi) ─────────────────────────
        $donasi_pending = Donasi::with(['donatur', 'pantiAsuhan'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        // ── GRAFIK donasi per bulan (12 bulan terakhir) ───────────────
        $donasi_per_bulan = Donasi::select(
                DB::raw('MONTH(tanggal_donasi) as bulan'),
                DB::raw('YEAR(tanggal_donasi) as tahun'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN jenis_donasi="uang" THEN nominal ELSE 0 END) as total_uang')
            )
            ->where('status', 'diterima')
            ->where('tanggal_donasi', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // ── PANTI dengan donasi terbanyak ─────────────────────────────
        $panti_top = PantiAsuhan::withCount(['donasi' => fn($q) => $q->where('status', 'diterima')])
            ->withSum(['keuangan as total_pemasukan' => fn($q) => $q->where('jenis', 'pemasukan')], 'nominal')
            ->orderByDesc('donasi_count')
            ->take(5)
            ->get();

        // ── DONATUR terbaru ───────────────────────────────────────────
        $donatur_terbaru = Donatur::with('donasi')
            ->withCount('donasi')
            ->latest()
            ->take(5)
            ->get();

        // ── KEUANGAN terbaru ──────────────────────────────────────────
        $keuangan_terbaru = Keuangan::with('pantiAsuhan')
            ->latest()
            ->take(6)
            ->get();

        // ── DISTRIBUSI jenis donasi ───────────────────────────────────
        $distribusi_jenis = [
            'uang'   => Donasi::diterima()->uang()->count(),
            'barang' => Donasi::diterima()->barang()->count(),
        ];

        // ── DISTRIBUSI metode donasi ──────────────────────────────────
        $distribusi_metode = Donasi::diterima()
            ->select('metode', DB::raw('COUNT(*) as total'))
            ->groupBy('metode')
            ->pluck('total', 'metode');

        return view('pages.dinsos_dashboard', compact(
            'stats',
            'donasi_terbaru',
            'donasi_pending',
            'donasi_per_bulan',
            'panti_top',
            'donatur_terbaru',
            'keuangan_terbaru',
            'distribusi_jenis',
            'distribusi_metode'
        ));
    }
}

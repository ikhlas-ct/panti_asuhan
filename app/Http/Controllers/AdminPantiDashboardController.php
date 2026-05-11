<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Keuangan;
use App\Models\Pengurus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminPantiDashboardController extends Controller
{
    public function index()
    {
        // Ambil panti milik admin yang login
        $panti = Auth::user()->pengurus->pantiAsuhan;
        $pantiId = $panti->id;

        // ── STATISTIK ANAK ASUH ───────────────────────────────────
        $anakQuery   = AnakAsuh::where('panti_asuhan_id', $pantiId);
        $anakAktif   = (clone $anakQuery)->where('status', 'aktif');

        $stats = [
            // Anak
            'total_anak'      => $anakQuery->count(),
            'anak_aktif'      => $anakAktif->count(),
            'anak_laki'       => (clone $anakAktif)->where('jenis_kelamin', 'L')->count(),
            'anak_perempuan'  => (clone $anakAktif)->where('jenis_kelamin', 'P')->count(),
            'anak_dalam'      => (clone $anakAktif)->where('jenis_tinggal', 'dalam')->count(),
            'anak_luar'       => (clone $anakAktif)->where('jenis_tinggal', 'luar')->count(),

            // Pengurus
            'total_pengurus'  => Pengurus::where('panti_asuhan_id', $pantiId)->count(),
            'pengurus_aktif'  => Pengurus::where('panti_asuhan_id', $pantiId)->where('status', 'aktif')->count(),

            // Donasi
            'total_donasi'    => Donasi::where('panti_asuhan_id', $pantiId)->count(),
            'donasi_diterima' => Donasi::where('panti_asuhan_id', $pantiId)->where('status', 'diterima')->count(),
            'donasi_pending'  => Donasi::where('panti_asuhan_id', $pantiId)->where('status', 'pending')->count(),

            // Keuangan
            'total_pemasukan'   => Keuangan::where('panti_asuhan_id', $pantiId)->where('jenis', 'pemasukan')->sum('nominal'),
            'total_pengeluaran' => Keuangan::where('panti_asuhan_id', $pantiId)->where('jenis', 'pengeluaran')->sum('nominal'),
        ];

        // ── DONASI TERBARU ────────────────────────────────────────
        $donasi_terbaru = Donasi::with(['donatur', 'barang'])
            ->where('panti_asuhan_id', $pantiId)
            ->latest()
            ->take(7)
            ->get();

        // ── DONASI PENDING ────────────────────────────────────────
        $donasi_pending = Donasi::with('donatur')
            ->where('panti_asuhan_id', $pantiId)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // ── ANAK ASUH TERBARU ─────────────────────────────────────
        $anak_terbaru = AnakAsuh::where('panti_asuhan_id', $pantiId)
            ->latest()
            ->take(5)
            ->get();

        // ── DONATUR YANG PERNAH DONASI KE PANTI INI ───────────────
        $donatur_terbaru = Donatur::whereHas('donasi', fn($q) => $q->where('panti_asuhan_id', $pantiId))
            ->withCount(['donasi as donasi_count' => fn($q) => $q->where('panti_asuhan_id', $pantiId)])
            ->orderByDesc('donasi_count')
            ->take(5)
            ->get();

        // ── KEUANGAN TERBARU ──────────────────────────────────────
        $keuangan_terbaru = Keuangan::where('panti_asuhan_id', $pantiId)
            ->latest('tanggal')
            ->take(6)
            ->get();

        // ── GRAFIK: Donasi per Bulan (12 bulan terakhir) ──────────
        $donasi_per_bulan = Donasi::where('panti_asuhan_id', $pantiId)
            ->where('status', 'diterima')
            ->where('tanggal_donasi', '>=', now()->subMonths(12)->startOfMonth())
            ->selectRaw('MONTH(tanggal_donasi) as bulan, YEAR(tanggal_donasi) as tahun, COUNT(*) as total, SUM(CASE WHEN jenis_donasi = "uang" THEN nominal ELSE 0 END) as total_uang')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // ── DISTRIBUSI: Jenis Donasi ──────────────────────────────
        $distribusi_jenis = Donasi::where('panti_asuhan_id', $pantiId)
            ->where('status', 'diterima')
            ->selectRaw('jenis_donasi, COUNT(*) as total')
            ->groupBy('jenis_donasi')
            ->pluck('total', 'jenis_donasi');

        // ── DISTRIBUSI: Anak Asuh (Jenis Kelamin) ────────────────
        $distribusi_anak = [
            'laki'      => AnakAsuh::where('panti_asuhan_id', $pantiId)->where('status', 'aktif')->where('jenis_kelamin', 'L')->count(),
            'perempuan' => AnakAsuh::where('panti_asuhan_id', $pantiId)->where('status', 'aktif')->where('jenis_kelamin', 'P')->count(),
        ];

        return view('pages.pengurus.dashboard', compact(
            'panti',
            'stats',
            'donasi_terbaru',
            'donasi_pending',
            'anak_terbaru',
            'donatur_terbaru',
            'keuangan_terbaru',
            'donasi_per_bulan',
            'distribusi_jenis',
            'distribusi_anak',
        ));
    }
}

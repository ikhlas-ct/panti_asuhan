<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonaturDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $donatur = $user->donatur;

        // Jika user belum punya profil donatur
        if (! $donatur) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil donatur Anda belum dibuat. Hubungi administrator.');
        }

        $donaturId = $donatur->id;

        // ── STAT UTAMA ─────────────────────────────────────────────
        $base = Donasi::where('donatur_id', $donaturId);

        $stats = [
            'total'         => (clone $base)->count(),
            'pending'       => (clone $base)->where('status', 'pending')->count(),
            'diterima'      => (clone $base)->where('status', 'diterima')->count(),
            'ditolak'       => (clone $base)->where('status', 'ditolak')->count(),
            'total_uang'    => (clone $base)->where('status', 'diterima')->where('jenis_donasi', 'uang')->sum('nominal'),
            'total_barang'  => (clone $base)->where('status', 'diterima')->where('jenis_donasi', 'barang')->count(),
            'total_online'  => (clone $base)->where('metode', 'online')->count(),
            'total_kunjungan' => (clone $base)->where('metode', 'kunjungan')->count(),
        ];

        // ── DONASI TERBARU ─────────────────────────────────────────
        $donasi_terbaru = Donasi::with(['pantiAsuhan', 'barang'])
            ->where('donatur_id', $donaturId)
            ->latest()
            ->take(8)
            ->get();

        // ── DONASI PENDING milik donatur ini ───────────────────────
        $donasi_pending = Donasi::with('pantiAsuhan')
            ->where('donatur_id', $donaturId)
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        // ── GRAFIK donasi per bulan (12 bulan terakhir) ────────────
        $donasi_per_bulan = Donasi::select(
                DB::raw('MONTH(tanggal_donasi) as bulan'),
                DB::raw('YEAR(tanggal_donasi) as tahun'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN jenis_donasi = "uang" AND status = "diterima" THEN nominal ELSE 0 END) as total_uang')
            )
            ->where('donatur_id', $donaturId)
            ->where('tanggal_donasi', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        // ── PANTI yang pernah menerima donasi dari donatur ini ─────
        $panti_list = Donasi::with('pantiAsuhan')
            ->where('donatur_id', $donaturId)
            ->where('status', 'diterima')
            ->select('panti_asuhan_id', DB::raw('COUNT(*) as total_donasi'), DB::raw('SUM(CASE WHEN jenis_donasi="uang" THEN nominal ELSE 0 END) as total_uang'))
            ->groupBy('panti_asuhan_id')
            ->orderByDesc('total_donasi')
            ->take(5)
            ->get();

        // ── Distribusi jenis donasi ────────────────────────────────
        $distribusi_jenis = [
            'uang'   => (clone $base)->where('jenis_donasi', 'uang')->count(),
            'barang' => (clone $base)->where('jenis_donasi', 'barang')->count(),
        ];

        // ── Distribusi status donasi ───────────────────────────────
        $distribusi_status = [
            'pending'  => $stats['pending'],
            'diterima' => $stats['diterima'],
            'ditolak'  => $stats['ditolak'],
        ];

        return view('pages.donatur.dashboard', compact(
            'donatur',
            'stats',
            'donasi_terbaru',
            'donasi_pending',
            'donasi_per_bulan',
            'panti_list',
            'distribusi_jenis',
            'distribusi_status'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PantiAsuhan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Halaman pusat laporan — hanya admin_dinsos.
     * Berisi 3 modal cetak: Donatur, Donasi, Keuangan.
     */
    public function index()
    {
        $pantis = PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        return view('pages.laporan.index', compact('pantis'));
    }
}

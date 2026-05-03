<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\PantiAsuhan;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    /**
     * Ambil panti_asuhan_id berdasarkan role user.
     * admin_panti → hanya pantiya sendiri
     * admin_dinsos → bisa semua, bisa filter
     */
    private function getPantiId(): ?int
    {
        $user = Auth::user();
        if ($user->isAdminPanti()) {
            return $user->pengurus?->panti_asuhan_id;
        }
        return null; // admin_dinsos: null = semua panti
    }

    // ──────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user       = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId    = $this->getPantiId();

        // Jika admin_panti tapi belum punya panti
        if ($isAdminPanti && !$pantiId) {
            abort(403, 'Akun Anda belum terhubung ke panti asuhan.');
        }

        $query = Keuangan::with(['pantiAsuhan', 'donasi'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        // Batasi ke panti sendiri kalau admin_panti
        if ($isAdminPanti) {
            $query->where('panti_asuhan_id', $pantiId);
        } else {
            // admin_dinsos bisa filter per panti
            if ($request->filled('panti_asuhan_id')) {
                $query->where('panti_asuhan_id', $request->panti_asuhan_id);
                $pantiId = $request->panti_asuhan_id;
            }
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter bulan-tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Search keterangan
        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%' . $request->search . '%');
        }

        $keuangans = $query->paginate(15)->withQueryString();

        // Hitung saldo ringkasan (sesuai filter panti yang dipilih)
        $summaryQuery = Keuangan::query();
        if ($isAdminPanti) {
            $summaryQuery->where('panti_asuhan_id', $pantiId);
        } elseif ($request->filled('panti_asuhan_id')) {
            $summaryQuery->where('panti_asuhan_id', $request->panti_asuhan_id);
        }
        $totalPemasukan  = (clone $summaryQuery)->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $summaryQuery)->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo           = $totalPemasukan - $totalPengeluaran;

        // Dropdown list panti (hanya untuk admin_dinsos)
        $pantis = $isAdminPanti ? collect() : PantiAsuhan::orderBy('nama_panti')->get();

        return view('pages.keuangan.index', compact(
            'keuangans', 'pantis', 'isAdminPanti',
            'totalPemasukan', 'totalPengeluaran', 'saldo'
        ));
    }

    // ──────────────────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────────────────
    public function create()
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId      = $this->getPantiId();

        if ($isAdminPanti && !$pantiId) {
            abort(403, 'Akun Anda belum terhubung ke panti asuhan.');
        }

        $pantis  = $isAdminPanti ? collect() : PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        // Donasi uang yg sudah diterima & belum punya keuangan pemasukan
        // — untuk link otomatis dari donasi
        $donasiQuery = Donasi::where('jenis_donasi', 'uang')
            ->where('status', 'diterima')
            ->whereDoesntHave('keuangan'); // asumsi ada relasi keuangan di model Donasi

        if ($isAdminPanti) {
            $donasiQuery->where('panti_asuhan_id', $pantiId);
        }

        $donasiList = $donasiQuery->with('donatur')->orderBy('tanggal_donasi', 'desc')->get();

        return view('pages.keuangan.create', compact('pantis', 'isAdminPanti', 'pantiId', 'donasiList'));
    }

    // ──────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $myPantiId    = $this->getPantiId();

        // Tentukan panti_asuhan_id
        $pantiAsuhanId = $isAdminPanti ? $myPantiId : $request->panti_asuhan_id;

        $request->merge(['panti_asuhan_id' => $pantiAsuhanId]);

        $rules = [
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'nullable|string|max:50',
            'keterangan'      => 'nullable|string|max:255',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'donasi_id'       => 'nullable|exists:donasi,id',
            'bukti'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        // Kalau jenis pemasukan & donasi_id diisi, ambil nominal dari donasi
        $validated = $request->validate($rules);

        // Auto-fill dari donasi jika ada
        if (!empty($validated['donasi_id'])) {
            $donasi = Donasi::findOrFail($validated['donasi_id']);
            $validated['nominal']         = $donasi->nominal;
            $validated['keterangan']      = $validated['keterangan'] ?: 'Pemasukan dari donasi - ' . $donasi->donatur->nama;
            $validated['jenis']           = 'pemasukan';
            $validated['panti_asuhan_id'] = $donasi->panti_asuhan_id;
            $validated['tanggal']         = $validated['tanggal'] ?: $donasi->tanggal_donasi;
        }

        // Upload bukti
        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('keuangan/bukti', 'public');
        }

        Keuangan::create($validated);

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    // ──────────────────────────────────────────────────────────
    // SHOW
    // ──────────────────────────────────────────────────────────
    public function show(Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);

        $keuangan->load(['pantiAsuhan', 'donasi.donatur']);

        return view('pages.keuangan.show', compact('keuangan'));
    }

    // ──────────────────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────────────────
    public function edit(Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);

        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId      = $this->getPantiId();
        $pantis       = $isAdminPanti ? collect() : PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        $donasiQuery = Donasi::where('jenis_donasi', 'uang')
            ->where('status', 'diterima')
            ->where(function ($q) use ($keuangan) {
                // Sertakan donasi yang saat ini terpilih meski sudah punya keuangan
                $q->whereDoesntHave('keuangan')
                  ->orWhere('id', $keuangan->donasi_id);
            });

        if ($isAdminPanti) {
            $donasiQuery->where('panti_asuhan_id', $pantiId);
        }

        $donasiList = $donasiQuery->with('donatur')->orderBy('tanggal_donasi', 'desc')->get();

        return view('pages.keuangan.edit', compact(
            'keuangan', 'pantis', 'isAdminPanti', 'pantiId', 'donasiList'
        ));
    }

    // ──────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────
    public function update(Request $request, Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);

        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $myPantiId    = $this->getPantiId();

        $pantiAsuhanId = $isAdminPanti ? $myPantiId : $request->panti_asuhan_id;
        $request->merge(['panti_asuhan_id' => $pantiAsuhanId]);

        $validated = $request->validate([
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'nullable|string|max:50',
            'keterangan'      => 'nullable|string|max:255',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'donasi_id'       => 'nullable|exists:donasi,id',
            'bukti'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Auto-fill dari donasi
        if (!empty($validated['donasi_id'])) {
            $donasi = Donasi::findOrFail($validated['donasi_id']);
            $validated['nominal'] = $donasi->nominal;
            $validated['jenis']   = 'pemasukan';
        }

        // Upload bukti baru
        if ($request->hasFile('bukti')) {
            if ($keuangan->bukti) {
                Storage::disk('public')->delete($keuangan->bukti);
            }
            $validated['bukti'] = $request->file('bukti')->store('keuangan/bukti', 'public');
        }

        $keuangan->update($validated);

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────
    public function destroy(Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);

        if ($keuangan->bukti) {
            Storage::disk('public')->delete($keuangan->bukti);
        }

        $keuangan->delete();

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus.');
    }

    // ──────────────────────────────────────────────────────────
    // AJAX: load donasi by panti (untuk admin_dinsos)
    // ──────────────────────────────────────────────────────────
    public function getDonasiByPanti(Request $request)
    {
        $pantiId = $request->panti_asuhan_id;

        $donasis = Donasi::where('panti_asuhan_id', $pantiId)
            ->where('jenis_donasi', 'uang')
            ->where('status', 'diterima')
            ->whereDoesntHave('keuangan')
            ->with('donatur')
            ->orderBy('tanggal_donasi', 'desc')
            ->get()
            ->map(fn($d) => [
                'id'      => $d->id,
                'label'   => $d->donatur->nama . ' — Rp ' . number_format($d->nominal, 0, ',', '.') . ' (' . $d->tanggal_donasi . ')',
                'nominal' => $d->nominal,
            ]);

        return response()->json($donasis);
    }

    // ──────────────────────────────────────────────────────────
    // HELPER: cek hak akses
    // ──────────────────────────────────────────────────────────
    private function authorizeAccess(Keuangan $keuangan): void
    {
        $user = Auth::user();
        if ($user->isAdminPanti()) {
            $pantiId = $this->getPantiId();
            if ($keuangan->panti_asuhan_id !== $pantiId) {
                abort(403);
            }
        }
    }
}

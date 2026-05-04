<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\PantiAsuhan;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    // ── Helper: ambil panti_id sesuai role ───────────────────
    private function getPantiId(): ?int
    {
        $user = Auth::user();
        if ($user->isAdminPanti()) {
            return $user->pengurus?->panti_asuhan_id;
        }
        return null;
    }

    private function authorizeAccess(Keuangan $keuangan): void
    {
        $user = Auth::user();
        if ($user->isAdminPanti()) {
            if ($keuangan->panti_asuhan_id !== $this->getPantiId()) {
                abort(403);
            }
        }
    }

    // ──────────────────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId      = $this->getPantiId();

        if ($isAdminPanti && !$pantiId) {
            abort(403, 'Akun Anda belum terhubung ke panti asuhan.');
        }

        $query = Keuangan::with(['pantiAsuhan', 'donasi.donatur'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        if ($isAdminPanti) {
            $query->where('panti_asuhan_id', $pantiId);
        } else {
            if ($request->filled('panti_asuhan_id')) {
                $query->where('panti_asuhan_id', $request->panti_asuhan_id);
            }
        }

        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);
        if ($request->filled('bulan'))  $query->whereMonth('tanggal', $request->bulan);
        if ($request->filled('tahun'))  $query->whereYear('tanggal', $request->tahun);
        if ($request->filled('search')) $query->where('keterangan', 'like', '%' . $request->search . '%');

        $keuangans = $query->paginate(15)->withQueryString();

        // Summary saldo
        $summaryQuery = Keuangan::query();
        if ($isAdminPanti) {
            $summaryQuery->where('panti_asuhan_id', $pantiId);
        } elseif ($request->filled('panti_asuhan_id')) {
            $summaryQuery->where('panti_asuhan_id', $request->panti_asuhan_id);
        }
        $totalPemasukan   = (clone $summaryQuery)->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = (clone $summaryQuery)->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo            = $totalPemasukan - $totalPengeluaran;

        $pantis = $isAdminPanti ? collect() : PantiAsuhan::orderBy('nama_panti')->get();

        return view('pages.keuangan.index', compact(
            'keuangans',
            'pantis',
            'isAdminPanti',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo'
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

        $pantis      = $isAdminPanti ? collect() : PantiAsuhan::aktif()->orderBy('nama_panti')->get();
        $donaturList = Donatur::aktif()->orderBy('nama')->get();

        return view('pages.keuangan.create', compact(
            'pantis',
            'isAdminPanti',
            'pantiId',
            'donaturList'
        ));
    }

    // ──────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $myPantiId    = $this->getPantiId();

        $pantiAsuhanId = $isAdminPanti ? $myPantiId : $request->panti_asuhan_id;
        $request->merge(['panti_asuhan_id' => $pantiAsuhanId]);

        $isDonasi = $request->input('sumber') === 'donasi';

        // ── Validasi ─────────────────────────────────────────
        $rules = [
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'nullable|string|max:50',
            'keterangan'      => 'nullable|string|max:255',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'bukti'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($isDonasi) {
            $rules['donatur_id']        = 'required|exists:donatur,id';
            $rules['metode']            = 'required|in:online,kunjungan';
            $rules['catatan']           = 'nullable|string|max:500';
            $rules['bukti_transfer']    = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
            $rules['tanggal_kunjungan'] = 'nullable|date|required_if:metode,kunjungan';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $isDonasi, $pantiAsuhanId) {
            $donasi = null;

            // ── 1. Buat record Donasi ────────────────────────
            if ($isDonasi) {
                $buktiTransferPath = null;
                if ($request->hasFile('bukti_transfer')) {
                    $buktiTransferPath = $request->file('bukti_transfer')
                        ->store('donasi/bukti_transfer', 'public');
                }

                $donasi = Donasi::create([
                    'donatur_id'        => $validated['donatur_id'],
                    'panti_asuhan_id'   => $pantiAsuhanId,
                    'jenis_donasi'      => 'uang',
                    'metode'            => $validated['metode'],
                    'nominal'           => $validated['nominal'],
                    'bukti_transfer'    => $buktiTransferPath,
                    'tanggal_donasi'    => $validated['tanggal'],
                    'tanggal_kunjungan' => $validated['tanggal_kunjungan'] ?? null,
                    'catatan'           => $validated['catatan'] ?? null,
                    'status'            => 'diterima',
                    'dikonfirmasi_oleh' => Auth::id(),
                    'dikonfirmasi_at'   => now(),
                ]);
            }

            // ── 2. Upload bukti keuangan ─────────────────────
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('keuangan/bukti', 'public');
            } elseif ($isDonasi && isset($donasi) && $donasi->bukti_transfer) {
                // Gunakan bukti transfer sebagai bukti keuangan jika tidak ada upload terpisah
                $buktiPath = $donasi->bukti_transfer;
            }

            // ── 3. Keterangan otomatis jika donasi ──────────
            $keterangan = $validated['keterangan'] ?? null;
            if ($isDonasi && !$keterangan) {
                $donatur    = Donatur::find($validated['donatur_id']);
                $keterangan = 'Pemasukan donasi dari ' . ($donatur->nama ?? '-');
            }

            // ── 4. Buat record Keuangan ──────────────────────
            Keuangan::create([
                'panti_asuhan_id' => $pantiAsuhanId,
                'jenis'           => $isDonasi ? 'pemasukan' : $validated['jenis'],
                'kategori'        => $validated['kategori'] ?? ($isDonasi ? 'Donasi Uang' : null),
                'keterangan'      => $keterangan,
                'nominal'         => $validated['nominal'],
                'tanggal'         => $validated['tanggal'],
                'donasi_id'       => $donasi?->id,
                'bukti'           => $buktiPath,
            ]);
        });

        return redirect()->route('keuangan.index')
            ->with(
                'success',
                $isDonasi
                    ? 'Donasi & catatan keuangan berhasil disimpan.'
                    : 'Transaksi keuangan berhasil ditambahkan.'
            );
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
        $keuangan->load(['donasi.donatur']);

        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId      = $this->getPantiId();
        $pantis       = $isAdminPanti ? collect() : PantiAsuhan::aktif()->orderBy('nama_panti')->get();
        $donaturList  = Donatur::aktif()->orderBy('nama')->get();

        return view('pages.keuangan.edit', compact(
            'keuangan',
            'pantis',
            'isAdminPanti',
            'pantiId',
            'donaturList'
        ));
    }

    // ──────────────────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────────────────
    public function update(Request $request, Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);
        $keuangan->load('donasi');

        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $myPantiId    = $this->getPantiId();

        $pantiAsuhanId = $isAdminPanti ? $myPantiId : $request->panti_asuhan_id;
        $request->merge(['panti_asuhan_id' => $pantiAsuhanId]);

        // Jika record ini sudah punya donasi, tetap donasi
        $isDonasi = $keuangan->donasi_id !== null || $request->input('sumber') === 'donasi';

        $rules = [
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis'           => 'required|in:pemasukan,pengeluaran',
            'kategori'        => 'nullable|string|max:50',
            'keterangan'      => 'nullable|string|max:255',
            'nominal'         => 'required|numeric|min:1',
            'tanggal'         => 'required|date',
            'bukti'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        if ($isDonasi) {
            $rules['donatur_id']        = 'required|exists:donatur,id';
            $rules['metode']            = 'required|in:online,kunjungan';
            $rules['catatan']           = 'nullable|string|max:500';
            $rules['bukti_transfer']    = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
            $rules['tanggal_kunjungan'] = 'nullable|date|required_if:metode,kunjungan';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $isDonasi, $pantiAsuhanId, $keuangan) {

            // ── Update Donasi jika ada ───────────────────────
            if ($isDonasi && $keuangan->donasi) {
                $donasi         = $keuangan->donasi;
                $buktiTransfer  = $donasi->bukti_transfer;

                if ($request->hasFile('bukti_transfer')) {
                    if ($buktiTransfer) Storage::disk('public')->delete($buktiTransfer);
                    $buktiTransfer = $request->file('bukti_transfer')
                        ->store('donasi/bukti_transfer', 'public');
                }

                $donasi->update([
                    'donatur_id'        => $validated['donatur_id'],
                    'panti_asuhan_id'   => $pantiAsuhanId,
                    'nominal'           => $validated['nominal'],
                    'metode'            => $validated['metode'],
                    'tanggal_donasi'    => $validated['tanggal'],
                    'tanggal_kunjungan' => $validated['tanggal_kunjungan'] ?? null,
                    'catatan'           => $validated['catatan'] ?? null,
                    'bukti_transfer'    => $buktiTransfer,
                ]);
            }

            // ── Bukti keuangan ───────────────────────────────
            $buktiPath = $keuangan->bukti;

            if ($request->hasFile('bukti')) {
                if ($keuangan->bukti) Storage::disk('public')->delete($keuangan->bukti);
                $buktiPath = $request->file('bukti')->store('keuangan/bukti', 'public');
            }

            if ($request->boolean('hapus_bukti') && $keuangan->bukti) {
                Storage::disk('public')->delete($keuangan->bukti);
                $buktiPath = null;
            }

            // ── Update Keuangan ──────────────────────────────
            $keuangan->update([
                'panti_asuhan_id' => $pantiAsuhanId,
                'jenis'           => $isDonasi ? 'pemasukan' : $validated['jenis'],
                'kategori'        => $validated['kategori'] ?? ($isDonasi ? 'Donasi Uang' : null),
                'keterangan'      => $validated['keterangan'],
                'nominal'         => $validated['nominal'],
                'tanggal'         => $validated['tanggal'],
                'bukti'           => $buktiPath,
            ]);
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────────────────
    public function destroy(Keuangan $keuangan)
    {
        $this->authorizeAccess($keuangan);
        $keuangan->load('donasi');

        DB::transaction(function () use ($keuangan) {
            if ($keuangan->bukti) {
                Storage::disk('public')->delete($keuangan->bukti);
            }

            if ($keuangan->donasi) {
                if ($keuangan->donasi->bukti_transfer) {
                    Storage::disk('public')->delete($keuangan->donasi->bukti_transfer);
                }
                $keuangan->donasi->delete();
            }

            $keuangan->delete();
        });

        return redirect()->route('keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus.');
    }



    public function laporanForm()
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $pantiId      = $this->getPantiId();

        if ($isAdminPanti && !$pantiId) {
            abort(403, 'Akun Anda belum terhubung ke panti asuhan.');
        }

        // Admin panti: hanya pantiya, admin dinsos: semua panti
        $pantis = $isAdminPanti
            ? PantiAsuhan::where('id', $pantiId)->get()
            : PantiAsuhan::orderBy('nama_panti')->get();

        return view('pages.keuangan.laporan-form', compact('pantis', 'isAdminPanti', 'pantiId'));
    }



    public function laporanCetak(Request $request)
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();
        $myPantiId    = $this->getPantiId();

        $request->validate([
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'bulan'           => 'nullable|integer|between:1,12',
            'tahun'           => 'nullable|integer|min:2000|max:2099',
        ]);

        $pantiId = $isAdminPanti ? $myPantiId : $request->panti_asuhan_id;

        if ($isAdminPanti && $pantiId !== $myPantiId) {
            abort(403);
        }

        $panti = PantiAsuhan::findOrFail($pantiId);

        // Query transaksi
        $query = Keuangan::where('panti_asuhan_id', $pantiId)
            ->orderBy('tanggal')
            ->orderBy('id');

        if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
        if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);

        $transaksis       = $query->get();
        $totalPemasukan   = $transaksis->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo            = $totalPemasukan - $totalPengeluaran;
        $bulan            = $request->bulan;
        $tahun            = $request->tahun;

        $settings = \App\Models\WebsiteSetting::first();

        // ── Ambil nama kepala dinsos dari tabel pegawai ──────────
        // Cari pegawai yang posisinya kepala / jabatan tertinggi,
        // atau ambil berdasarkan user yang role-nya admin_dinsos
        $kepalaDinsos = \App\Models\Pegawai::whereHas(
            'user',
            fn($q) =>
            $q->where('role', 'admin_dinsos')
        )->orderByDesc('id_pegawai')->first();

        return view('pages.keuangan.laporan-cetak', compact(
            'panti',
            'transaksis',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'bulan',
            'tahun',
            'settings',
            'kepalaDinsos'
        ));
    }


}

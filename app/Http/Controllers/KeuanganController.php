<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\Donatur;
use App\Models\Keuangan;
use App\Models\PantiAsuhan;
use App\Models\Pegawai;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
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

            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('keuangan/bukti', 'public');
            } elseif ($isDonasi && isset($donasi) && $donasi->bukti_transfer) {
                $buktiPath = $donasi->bukti_transfer;
            }

            $keterangan = $validated['keterangan'] ?? null;
            if ($isDonasi && !$keterangan) {
                $donatur    = Donatur::find($validated['donatur_id']);
                $keterangan = 'Pemasukan donasi dari ' . ($donatur->nama ?? '-');
            }

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

            if ($isDonasi && $keuangan->donasi) {
                $donasi        = $keuangan->donasi;
                $buktiTransfer = $donasi->bukti_transfer;

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

            $buktiPath = $keuangan->bukti;

            if ($request->hasFile('bukti')) {
                if ($keuangan->bukti) Storage::disk('public')->delete($keuangan->bukti);
                $buktiPath = $request->file('bukti')->store('keuangan/bukti', 'public');
            }

            if ($request->boolean('hapus_bukti') && $keuangan->bukti) {
                Storage::disk('public')->delete($keuangan->bukti);
                $buktiPath = null;
            }

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

    // ──────────────────────────────────────────────────────────
    // LAPORAN CETAK
    // Route: GET /keuangan/laporan/cetak  → name: keuangan.laporan.cetak
    // ──────────────────────────────────────────────────────────
    public function laporanCetak(Request $request)
    {
        $user         = Auth::user();
        $isAdminPanti = $user->isAdminPanti();

        // ── Tentukan panti ────────────────────────────────────
        if ($isAdminPanti) {
            $pantiId = $user->pengurus?->panti_asuhan_id;
            abort_unless($pantiId, 403, 'Akun belum terhubung ke panti asuhan.');
        } else {
            $request->validate([
                'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            ], [
                'panti_asuhan_id.required' => 'Silakan pilih panti asuhan terlebih dahulu.',
            ]);
            $pantiId = $request->panti_asuhan_id;
        }

        $panti = PantiAsuhan::findOrFail($pantiId);

        // ── Tipe periode & rentang tanggal ────────────────────
        $tipe = $request->input('tipe_periode', 'harian');

        switch ($tipe) {
            case 'harian':
                $tanggal      = Carbon::parse($request->input('tanggal', today()))->startOfDay();
                $dateFrom     = $tanggal->copy()->startOfDay();
                $dateTo       = $tanggal->copy()->endOfDay();
                $labelPeriode = $tanggal->translatedFormat('d F Y');
                break;

            case 'bulanan':
                $bulan        = (int) $request->input('bulan_laporan',  now()->month);
                $tahun        = (int) $request->input('tahun_laporan',  now()->year);
                $dateFrom     = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $dateTo       = $dateFrom->copy()->endOfMonth();
                $labelPeriode = $dateFrom->translatedFormat('F Y');
                break;

            case 'tahunan':
            default:
                $tahun        = (int) $request->input('tahun_laporan_only', now()->year);
                $dateFrom     = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
                $dateTo       = $dateFrom->copy()->endOfYear();
                $labelPeriode = (string) $tahun;
                break;
        }

        // ── Query transaksi ───────────────────────────────────
        $transaksi = Keuangan::where('panti_asuhan_id', $pantiId)
            ->whereBetween('tanggal', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        // ── Hitung totals ─────────────────────────────────────
        $totalPemasukan   = $transaksi->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksi->where('jenis', 'pengeluaran')->sum('nominal');
        $saldo            = $totalPemasukan - $totalPengeluaran;

        // ── Data pendukung ────────────────────────────────────
        // WebsiteSetting: nama institusi, logo, alamat, slogan, dll.
        $setting = WebsiteSetting::getSetting();

        // Nama pengurus aktif panti (untuk tanda tangan)
        $pengurusNama = $panti->pengurus()->aktif()->first()?->nama;

        // Nama kepala dinas (opsional — dari Pegawai dengan posisi kepala)
        $kepaladinsos = Pegawai::where('posisi', 'like', '%kepala%')
            ->value('nama');

        return view('pages.keuangan.laporan-cetak', compact(
            'panti',
            'transaksi',
            'tipe',
            'labelPeriode',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'setting',
            'pengurusNama',
            'kepaladinsos'
        ));
    }
}

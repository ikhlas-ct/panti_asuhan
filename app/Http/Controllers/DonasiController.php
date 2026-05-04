<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use App\Models\DonasiBarang;
use App\Models\Donatur;
use App\Models\Keuangan;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DonasiController extends Controller
{
    /* ================================================================== */
    /*  HELPER – role yang bisa langsung approve tanpa verifikasi          */
    /* ================================================================== */
    private function canAutoApprove(): bool
    {
        return in_array(Auth::user()->role, ['admin_dinsos', 'admin_panti']);
    }

    /* ================================================================== */
    /*  HELPER – buat catatan keuangan (hanya untuk donasi UANG)          */
    /* ================================================================== */
    private function catatKeuangan(Donasi $donasi): void
    {
        if ($donasi->jenis_donasi !== 'uang') return;
        if ($donasi->keuangan()->exists()) return;

        $metodeLabel = $donasi->metode === 'online' ? 'Transfer/QRIS' : 'Kunjungan Langsung';

        Keuangan::create([
            'panti_asuhan_id' => $donasi->panti_asuhan_id,
            'jenis'           => 'pemasukan',
            'kategori'        => 'Donasi Uang',
            'keterangan'      => "Donasi uang dari {$donasi->donatur->nama} ({$metodeLabel})",
            'nominal'         => $donasi->nominal ?? 0,
            'tanggal'         => $donasi->tanggal_donasi ?? today(),
            'donasi_id'       => $donasi->id,
            'bukti'           => $donasi->bukti_transfer,
        ]);
    }

    /* ================================================================== */
    /*  INDEX                                                               */
    /* ================================================================== */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Donasi::with(['donatur', 'pantiAsuhan', 'barang'])->latest();

        // Batasi scope per role
        if ($user->role === 'admin_panti') {
            $query->where('panti_asuhan_id', $user->pengurus?->panti_asuhan_id);
        } elseif ($user->role === 'donatur') {
            $query->where('donatur_id', $user->donatur?->id);
        }

        // Filter
        if ($s = $request->search) {
            $query->whereHas('donatur', fn($q) => $q->where('nama', 'like', "%$s%"));
        }
        if ($j = $request->jenis_donasi)    $query->where('jenis_donasi', $j);
        if ($st = $request->status)          $query->where('status', $st);
        if ($m = $request->metode)           $query->where('metode', $m);
        if ($p = $request->panti_asuhan_id)  $query->where('panti_asuhan_id', $p);

        $donasis = $query->paginate(15)->withQueryString();
        $pantis  = PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        // Stats
        $sq = Donasi::query();
        if ($user->role === 'admin_panti')  $sq->where('panti_asuhan_id', $user->pengurus?->panti_asuhan_id);
        if ($user->role === 'donatur')       $sq->where('donatur_id', $user->donatur?->id);

        $stats = [
            'total'    => (clone $sq)->count(),
            'pending'  => (clone $sq)->where('status', 'pending')->count(),
            'diterima' => (clone $sq)->where('status', 'diterima')->count(),
            'ditolak'  => (clone $sq)->where('status', 'ditolak')->count(),
            'nominal'  => (clone $sq)->where('status', 'diterima')->where('jenis_donasi', 'uang')->sum('nominal'),
        ];

        return view('pages.donasi.index', compact('donasis', 'pantis', 'stats'));
    }

    /* ================================================================== */
    /*  CREATE                                                              */
    /* ================================================================== */
    public function create()
    {
        $user = Auth::user();

        $donaturList = $user->role === 'donatur'
            ? collect([$user->donatur])->filter()
            : Donatur::aktif()->orderBy('nama')->get();

        $pantis = $user->role === 'admin_panti'
            ? PantiAsuhan::where('id', $user->pengurus?->panti_asuhan_id)->get()
            : PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        return view('pages.donasi.create', compact('donaturList', 'pantis'));
    }

    /* ================================================================== */
    /*  STORE                                                               */
    /* ================================================================== */
    public function store(Request $request)
    {
        $user = Auth::user();

        // ── Validasi dasar ────────────────────────────────────────────
        $rules = [
            'donatur_id'      => 'required|exists:donatur,id',
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis_donasi'    => 'required|in:uang,barang',
            'metode'          => 'required|in:online,kunjungan',
            'tanggal_donasi'  => 'required|date',
            'catatan'         => 'nullable|string|max:500',
        ];

        // Kondisional: uang
        if ($request->jenis_donasi === 'uang') {
            $rules['nominal']        = 'required|numeric|min:1000';
            $rules['bukti_transfer'] = $request->metode === 'online'
                ? 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
                : 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        // Kondisional: barang
        if ($request->jenis_donasi === 'barang') {
            $rules['deskripsi_barang']      = 'nullable|string|max:500';
            $rules['tanggal_kunjungan']     = 'nullable|date';
            // Validasi array item barang
            $rules['barang']                = 'required|array|min:1';
            $rules['barang.*.nama_barang']  = 'required|string|max:100';
            $rules['barang.*.jumlah_barang'] = 'required|integer|min:1';
            $rules['barang.*.satuan_barang'] = 'nullable|string|max:50';
            $rules['barang.*.keterangan']   = 'nullable|string|max:255';
            $rules['barang.*.foto_barang']  = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        $validated = $request->validate($rules, [
            'donatur_id.required'           => 'Donatur wajib dipilih.',
            'panti_asuhan_id.required'      => 'Panti asuhan tujuan wajib dipilih.',
            'jenis_donasi.required'         => 'Jenis donasi wajib dipilih.',
            'metode.required'               => 'Metode donasi wajib dipilih.',
            'nominal.required'              => 'Nominal wajib diisi untuk donasi uang.',
            'nominal.min'                   => 'Nominal minimal Rp 1.000.',
            'bukti_transfer.required'       => 'Bukti transfer wajib diupload untuk donasi online.',
            'barang.required'               => 'Tambahkan minimal 1 item barang.',
            'barang.*.nama_barang.required' => 'Nama barang wajib diisi.',
            'barang.*.jumlah_barang.required' => 'Jumlah barang wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $validated, $user) {

            // Upload bukti transfer
            $buktiPath = null;
            if ($request->hasFile('bukti_transfer')) {
                $buktiPath = $request->file('bukti_transfer')->store('donasi/bukti', 'public');
            }

            // ── Status berdasarkan role ───────────────────────────────
            $isAutoApprove = $this->canAutoApprove();

            $donasi = Donasi::create([
                'donatur_id'        => $validated['donatur_id'],
                'panti_asuhan_id'   => $validated['panti_asuhan_id'],
                'jenis_donasi'      => $validated['jenis_donasi'],
                'metode'            => $validated['metode'],
                'nominal'           => $validated['nominal'] ?? null,
                'bukti_transfer'    => $buktiPath,
                'deskripsi_barang'  => $validated['deskripsi_barang'] ?? null,
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'] ?? null,
                'tanggal_donasi'    => $validated['tanggal_donasi'],
                'catatan'           => $validated['catatan'] ?? null,
                'status'            => $isAutoApprove ? 'diterima' : 'pending',
                'dikonfirmasi_oleh' => $isAutoApprove ? $user->id : null,
                'dikonfirmasi_at'   => $isAutoApprove ? now() : null,
            ]);

            // ── Simpan item barang ────────────────────────────────────
            if ($validated['jenis_donasi'] === 'barang' && !empty($validated['barang'])) {
                foreach ($validated['barang'] as $idx => $item) {
                    $fotoPath = null;
                    if ($request->hasFile("barang.{$idx}.foto_barang")) {
                        $fotoPath = $request->file("barang.{$idx}.foto_barang")
                            ->store('donasi/foto_barang', 'public');
                    }

                    DonasiBarang::create([
                        'donasi_id'     => $donasi->id,
                        'nama_barang'   => $item['nama_barang'],
                        'jumlah_barang' => $item['jumlah_barang'],
                        'satuan_barang' => $item['satuan_barang'] ?? null,
                        'keterangan'    => $item['keterangan'] ?? null,
                        'foto_barang'   => $fotoPath,
                    ]);
                }
            }

            // ── Jika auto-approve dan uang → catat keuangan ──────────
            if ($isAutoApprove) {
                $donasi->load('donatur');
                $this->catatKeuangan($donasi);
            }
        });

        return redirect()->route('donasi.index')
            ->with('success', $this->canAutoApprove()
                ? 'Donasi berhasil ditambahkan dan langsung diterima.'
                : 'Donasi berhasil dikirim. Menunggu verifikasi admin.');
    }

    /* ================================================================== */
    /*  SHOW                                                                */
    /* ================================================================== */
    public function show(Donasi $donasi)
    {
        $this->authorizeAccess($donasi);
        $donasi->load(['donatur', 'pantiAsuhan', 'dikonfirmasiOleh', 'keuangan', 'barang']);

        return view('pages.donasi.show', compact('donasi'));
    }

    /* ================================================================== */
    /*  EDIT                                                                */
    /* ================================================================== */
    public function edit(Donasi $donasi)
    {
        $this->authorizeAccess($donasi);

        if ($donasi->sudahDikonfirmasi()) {
            return redirect()->route('donasi.show', $donasi)
                ->with('error', 'Donasi yang sudah dikonfirmasi tidak dapat diedit.');
        }

        $user = Auth::user();

        $donaturList = $user->role === 'donatur'
            ? collect([$user->donatur])->filter()
            : Donatur::aktif()->orderBy('nama')->get();

        $pantis = $user->role === 'admin_panti'
            ? PantiAsuhan::where('id', $user->pengurus?->panti_asuhan_id)->get()
            : PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        $donasi->load('barang');

        return view('pages.donasi.edit', compact('donasi', 'donaturList', 'pantis'));
    }

    /* ================================================================== */
    /*  UPDATE                                                              */
    /* ================================================================== */
    public function update(Request $request, Donasi $donasi)
    {
        $this->authorizeAccess($donasi);

        if ($donasi->sudahDikonfirmasi()) {
            return redirect()->route('donasi.show', $donasi)
                ->with('error', 'Donasi yang sudah dikonfirmasi tidak dapat diedit.');
        }

        $rules = [
            'donatur_id'      => 'required|exists:donatur,id',
            'panti_asuhan_id' => 'required|exists:panti_asuhan,id',
            'jenis_donasi'    => 'required|in:uang,barang',
            'metode'          => 'required|in:online,kunjungan',
            'tanggal_donasi'  => 'required|date',
            'catatan'         => 'nullable|string|max:500',
        ];

        if ($request->jenis_donasi === 'uang') {
            $rules['nominal']        = 'required|numeric|min:1000';
            $rules['bukti_transfer'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        if ($request->jenis_donasi === 'barang') {
            $rules['deskripsi_barang']       = 'nullable|string|max:500';
            $rules['tanggal_kunjungan']      = 'nullable|date';
            $rules['barang']                 = 'required|array|min:1';
            $rules['barang.*.nama_barang']   = 'required|string|max:100';
            $rules['barang.*.jumlah_barang'] = 'required|integer|min:1';
            $rules['barang.*.satuan_barang'] = 'nullable|string|max:50';
            $rules['barang.*.keterangan']    = 'nullable|string|max:255';
            $rules['barang.*.foto_barang']   = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $donasi) {

            // Update bukti transfer jika ada file baru
            $buktiPath = $donasi->bukti_transfer;
            if ($request->hasFile('bukti_transfer')) {
                if ($buktiPath) Storage::disk('public')->delete($buktiPath);
                $buktiPath = $request->file('bukti_transfer')->store('donasi/bukti', 'public');
            }

            $donasi->update([
                'donatur_id'        => $validated['donatur_id'],
                'panti_asuhan_id'   => $validated['panti_asuhan_id'],
                'jenis_donasi'      => $validated['jenis_donasi'],
                'metode'            => $validated['metode'],
                'nominal'           => $validated['nominal'] ?? null,
                'bukti_transfer'    => $buktiPath,
                'deskripsi_barang'  => $validated['deskripsi_barang'] ?? null,
                'tanggal_kunjungan' => $validated['tanggal_kunjungan'] ?? null,
                'tanggal_donasi'    => $validated['tanggal_donasi'],
                'catatan'           => $validated['catatan'] ?? null,
            ]);

            // Update item barang: hapus semua lama, buat ulang
            if ($validated['jenis_donasi'] === 'barang') {
                // Hapus foto lama
                foreach ($donasi->barang as $b) {
                    if ($b->foto_barang) Storage::disk('public')->delete($b->foto_barang);
                }
                $donasi->barang()->delete();

                foreach ($validated['barang'] as $idx => $item) {
                    $fotoPath = null;
                    if ($request->hasFile("barang.{$idx}.foto_barang")) {
                        $fotoPath = $request->file("barang.{$idx}.foto_barang")
                            ->store('donasi/foto_barang', 'public');
                    }
                    DonasiBarang::create([
                        'donasi_id'     => $donasi->id,
                        'nama_barang'   => $item['nama_barang'],
                        'jumlah_barang' => $item['jumlah_barang'],
                        'satuan_barang' => $item['satuan_barang'] ?? null,
                        'keterangan'    => $item['keterangan'] ?? null,
                        'foto_barang'   => $fotoPath,
                    ]);
                }
            }
        });

        return redirect()->route('donasi.show', $donasi)
            ->with('success', 'Data donasi berhasil diperbarui.');
    }

    /* ================================================================== */
    /*  DESTROY                                                             */
    /* ================================================================== */
    public function destroy(Donasi $donasi)
    {
        $this->authorizeAccess($donasi);

        if ($donasi->sudahDikonfirmasi()) {
            return redirect()->route('donasi.index')
                ->with('error', 'Donasi yang sudah dikonfirmasi tidak dapat dihapus.');
        }

        // Hapus file
        if ($donasi->bukti_transfer) Storage::disk('public')->delete($donasi->bukti_transfer);
        foreach ($donasi->barang as $b) {
            if ($b->foto_barang) Storage::disk('public')->delete($b->foto_barang);
        }

        $donasi->delete(); // cascade ke donasi_barang

        return redirect()->route('donasi.index')
            ->with('success', 'Data donasi berhasil dihapus.');
    }

    /* ================================================================== */
    /*  KONFIRMASI – admin_dinsos & admin_panti                            */
    /*  Donasi uang → otomatis buat pemasukan keuangan                    */
    /* ================================================================== */
    public function konfirmasi(Request $request, Donasi $donasi)
    {
        if (!$this->canAutoApprove()) {
            abort(403, 'Anda tidak memiliki izin untuk memverifikasi donasi.');
        }

        if ($donasi->sudahDikonfirmasi()) {
            return back()->with('error', 'Donasi ini sudah pernah dikonfirmasi.');
        }

        DB::transaction(function () use ($donasi) {
            $donasi->update([
                'status'            => 'diterima',
                'dikonfirmasi_oleh' => Auth::id(),
                'dikonfirmasi_at'   => now(),
            ]);

            $donasi->load('donatur');
            $this->catatKeuangan($donasi);
        });

        $pesanTambahan = ($donasi->jenis_donasi === 'uang')
            ? ' Pemasukan keuangan otomatis tercatat.' : '';

        return redirect()->route('donasi.show', $donasi)
            ->with('success', "Donasi berhasil diterima.{$pesanTambahan}");
    }

    /* ================================================================== */
    /*  TOLAK – admin_dinsos & admin_panti                                 */
    /* ================================================================== */
    public function tolak(Request $request, Donasi $donasi)
    {
        if (!$this->canAutoApprove()) {
            abort(403, 'Anda tidak memiliki izin untuk menolak donasi.');
        }

        if ($donasi->sudahDikonfirmasi()) {
            return back()->with('error', 'Donasi ini sudah pernah dikonfirmasi.');
        }

        $request->validate([
            'alasan_tolak' => 'required|string|max:255',
        ], ['alasan_tolak.required' => 'Alasan penolakan wajib diisi.']);

        $donasi->update([
            'status'            => 'ditolak',
            'alasan_tolak'      => $request->alasan_tolak,
            'dikonfirmasi_oleh' => Auth::id(),
            'dikonfirmasi_at'   => now(),
        ]);

        return redirect()->route('donasi.show', $donasi)
            ->with('success', 'Donasi telah ditolak.');
    }

    /* ================================================================== */
    /*  PRIVATE HELPERS                                                     */
    /* ================================================================== */
    private function authorizeAccess(Donasi $donasi): void
    {
        $user = Auth::user();

        if ($user->role === 'donatur') {
            abort_if($donasi->donatur_id !== $user->donatur?->id, 403, 'Akses ditolak.');
        }

        if ($user->role === 'admin_panti') {
            abort_if($donasi->panti_asuhan_id !== $user->pengurus?->panti_asuhan_id, 403, 'Donasi ini bukan untuk panti Anda.');
        }
    }
}

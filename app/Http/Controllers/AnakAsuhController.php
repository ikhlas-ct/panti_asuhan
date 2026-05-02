<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnakAsuhController extends Controller
{
    /**
     * Ambil panti_asuhan_id berdasarkan role user yang login.
     * - admin_panti : hanya panti miliknya
     * - admin_dinsos: semua panti
     */
    private function getPantiId(): ?int
    {
        $user = Auth::user();

        if ($user->isAdminPanti()) {
            return $user->pengurus?->panti_asuhan_id;
        }

        return null;
    }

    private function baseQuery()
    {
        $pantiId = $this->getPantiId();

        $query = AnakAsuh::with('pantiAsuhan');

        if ($pantiId) {
            $query->where('panti_asuhan_id', $pantiId);
        }

        return $query;
    }

    // ── INDEX ─────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = $this->baseQuery();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis_tinggal
        if ($request->filled('jenis_tinggal')) {
            $query->where('jenis_tinggal', $request->jenis_tinggal);
        }

        // Filter jenis_kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter panti (hanya untuk admin_dinsos)
        if ($request->filled('panti_asuhan_id') && Auth::user()->isAdminDinsos()) {
            $query->where('panti_asuhan_id', $request->panti_asuhan_id);
        }

        // Search nama / nik
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $anakAsuhs = $query->latest()->paginate(15)->withQueryString();

        // Statistik ringkas
        $baseStats = $this->baseQuery();
        $stats = [
            'total'       => (clone $baseStats)->count(),
            'aktif'       => (clone $baseStats)->where('status', 'aktif')->count(),
            'dalam'       => (clone $baseStats)->where('jenis_tinggal', 'dalam')->count(),
            'luar'        => (clone $baseStats)->where('jenis_tinggal', 'luar')->count(),
        ];

        $pantis = Auth::user()->isAdminDinsos() ? PantiAsuhan::aktif()->get() : collect();

        return view('pages.anak.index', compact('anakAsuhs', 'stats', 'pantis'));
    }

    // ── CREATE ────────────────────────────────────────────────

    public function create()
    {
        $pantis   = Auth::user()->isAdminDinsos()
            ? PantiAsuhan::aktif()->get()
            : PantiAsuhan::where('id', $this->getPantiId())->get();

        $jenjangList = $this->jenjangPendidikanList();

        return view('pages.anak.create', compact('pantis', 'jenjangList'));
    }

    // ── STORE ─────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // Admin panti: paksa panti_asuhan_id miliknya
        if (Auth::user()->isAdminPanti()) {
            $validated['panti_asuhan_id'] = $this->getPantiId();
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('anak_asuh', 'public');
        }

        AnakAsuh::create($validated);

        return redirect()->route('anak-asuh.index')
            ->with('success', 'Data anak asuh berhasil ditambahkan.');
    }

    // ── SHOW ──────────────────────────────────────────────────

    public function show(AnakAsuh $anakAsuh)
    {
        $this->authorizeAccess($anakAsuh);

        $anakAsuh->load('pantiAsuhan');

        return view('pages.anak.show', compact('anakAsuh'));
    }

    // ── EDIT ──────────────────────────────────────────────────

    public function edit(AnakAsuh $anakAsuh)
    {
        $this->authorizeAccess($anakAsuh);

        $pantis = Auth::user()->isAdminDinsos()
            ? PantiAsuhan::aktif()->get()
            : PantiAsuhan::where('id', $this->getPantiId())->get();

        $jenjangList = $this->jenjangPendidikanList();

        return view('pages.anak.edit', compact('anakAsuh', 'pantis', 'jenjangList'));
    }

    // ── UPDATE ────────────────────────────────────────────────

    public function update(Request $request, AnakAsuh $anakAsuh)
    {
        $this->authorizeAccess($anakAsuh);

        $validated = $request->validate($this->rules($anakAsuh->id));

        if (Auth::user()->isAdminPanti()) {
            $validated['panti_asuhan_id'] = $this->getPantiId();
        }

        // Ganti foto jika ada upload baru
        if ($request->hasFile('foto')) {
            if ($anakAsuh->foto) {
                Storage::disk('public')->delete($anakAsuh->foto);
            }
            $validated['foto'] = $request->file('foto')->store('anak_asuh', 'public');
        }

        $anakAsuh->update($validated);

        return redirect()->route('anak-asuh.index')
            ->with('success', 'Data anak asuh berhasil diperbarui.');
    }

    // ── DESTROY ───────────────────────────────────────────────

    public function destroy(AnakAsuh $anakAsuh)
    {
        $this->authorizeAccess($anakAsuh);

        if ($anakAsuh->foto) {
            Storage::disk('public')->delete($anakAsuh->foto);
        }

        $anakAsuh->delete();

        return redirect()->route('anak-asuh.index')
            ->with('success', 'Data anak asuh berhasil dihapus.');
    }

    // ── HELPERS ───────────────────────────────────────────────

    /**
     * Pastikan admin_panti hanya bisa akses data pantinya sendiri.
     */
    private function authorizeAccess(AnakAsuh $anakAsuh): void
    {
        if (Auth::user()->isAdminPanti() &&
            $anakAsuh->panti_asuhan_id !== $this->getPantiId()) {
            abort(403, 'Akses ditolak.');
        }
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'panti_asuhan_id'    => ['required', 'exists:panti_asuhan,id'],
            'nama'               => ['required', 'string', 'max:255'],
            'nik'                => ['nullable', 'string', 'max:16', "unique:anak_asuh,nik,{$ignoreId}"],
            'no_kk'              => ['nullable', 'string', 'max:16'],
            'jenis_kelamin'      => ['required', 'in:L,P'],
            'tempat_lahir'       => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'      => ['nullable', 'date'],
            'agama'              => ['nullable', 'string', 'max:50'],
            'alamat_asal'        => ['nullable', 'string'],
            'asal_daerah'        => ['nullable', 'string', 'max:100'],
            'status_yatim'       => ['required', 'in:yatim,piatu,yatim_piatu,dhuafa'],
            'jenis_tinggal'      => ['required', 'in:dalam,luar'],
            'nama_ayah'          => ['nullable', 'string', 'max:255'],
            'nama_ibu'           => ['nullable', 'string', 'max:255'],
            'pekerjaan_ortu'     => ['nullable', 'string', 'max:100'],
            'no_telp_wali'       => ['nullable', 'string', 'max:20'],
            'jenjang_pendidikan' => ['nullable', 'string', 'max:50'],
            'nama_sekolah'       => ['nullable', 'string', 'max:255'],
            'kelas'              => ['nullable', 'string', 'max:20'],
            'tanggal_masuk'      => ['nullable', 'date'],
            'tanggal_keluar'     => ['nullable', 'date', 'after_or_equal:tanggal_masuk'],
            'alasan_keluar'      => ['nullable', 'string'],
            'foto'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'             => ['required', 'in:aktif,nonaktif,keluar'],
            'keterangan'         => ['nullable', 'string'],
        ];
    }

    private function jenjangPendidikanList(): array
    {
        return [
            'Belum Sekolah',
            'TK / PAUD',
            'SD / MI',
            'SMP / MTs',
            'SMA / MA / SMK',
            'Perguruan Tinggi',
            'Tidak Sekolah',
        ];
    }
}

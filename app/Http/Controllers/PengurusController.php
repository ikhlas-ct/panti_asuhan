<?php

namespace App\Http\Controllers;

use App\Models\Pengurus;
use App\Models\PantiAsuhan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PengurusController extends Controller
{
    // ──────────────────────────────────────────────
    //  INDEX
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Pengurus::with(['pantiAsuhan', 'user']);

        if (auth()->user()->isAdminPanti()) {
            $pantiId = auth()->user()->pengurus->panti_asuhan_id ?? null;
            $query->where('panti_asuhan_id', $pantiId);
        }

        if ($request->filled('panti_id') && auth()->user()->isAdminDinsos()) {
            $query->where('panti_asuhan_id', $request->panti_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('jabatan', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_telp', 'like', "%{$q}%");
            });
        }

        $pengurus       = $query->latest()->paginate(12)->withQueryString();
        $pantis         = auth()->user()->isAdminDinsos()
            ? PantiAsuhan::aktif()->orderBy('nama_panti')->get()
            : collect();
        $totalAktif     = Pengurus::where('status', 'aktif')->count();
        $totalNonaktif  = Pengurus::where('status', 'nonaktif')->count();
        $totalPunyaAkun = Pengurus::whereNotNull('user_id')->count();

        return view('pages.pengurus.index', compact(
            'pengurus',
            'pantis',
            'totalAktif',
            'totalNonaktif',
            'totalPunyaAkun'
        ));
    }

    // ──────────────────────────────────────────────
    //  CREATE
    // ──────────────────────────────────────────────
    public function create()
    {
        $pantis = PantiAsuhan::aktif()->orderBy('nama_panti')->get();

        return view('pages.pengurus.create', compact('pantis'));
    }

    // ──────────────────────────────────────────────
    //  STORE
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        /*
         * mode_akun:
         *   'none'  → simpan pengurus tanpa akun
         *   'baru'  → buat user baru sekaligus
         */
        $modeAkun = $request->input('mode_akun', 'none');

        $rules = [
            'panti_asuhan_id'     => 'required|exists:panti_asuhan,id',
            'nama'                => 'required|string|max:50',
            'nik'                 => 'nullable|string|max:16',
            'jenis_kelamin'       => 'required|in:L,P',
            'tempat_lahir'        => 'nullable|string|max:100',
            'tanggal_lahir'       => 'nullable|date|before:today',
            'no_telp'             => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:100',
            'alamat'              => 'nullable|string',
            'jabatan'             => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto'                => 'nullable|image|max:2048',
            'status'              => 'required|in:aktif,nonaktif',
        ];

        if ($modeAkun === 'baru') {
            $rules['akun_name']              = 'required|string|max:50';
            $rules['akun_email']             = 'required|email|max:100|unique:users,email';
            $rules['akun_password']          = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $modeAkun) {
            $userId = null;

            if ($modeAkun === 'baru') {
                $user   = User::create([
                    'username'     => $validated['akun_name'],
                    'email'    => $validated['akun_email'],
                    'password' => Hash::make($validated['akun_password']),
                    'role'     => 'admin_panti',
                    'status'   => 'aktif',
                ]);
                $userId = $user->id;
            }

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('pengurus', 'public');
            }

            Pengurus::create([
                'panti_asuhan_id'     => $validated['panti_asuhan_id'],
                'user_id'             => $userId,
                'nama'                => $validated['nama'],
                'nik'                 => $validated['nik'] ?? null,
                'jenis_kelamin'       => $validated['jenis_kelamin'],
                'tempat_lahir'        => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir'       => $validated['tanggal_lahir'] ?? null,
                'no_telp'             => $validated['no_telp'] ?? null,
                'email'               => $validated['email'] ?? null,
                'alamat'              => $validated['alamat'] ?? null,
                'jabatan'             => $validated['jabatan'] ?? null,
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
                'foto'                => $fotoPath,
                'status'              => $validated['status'],
            ]);
        });

        $msg = 'Data pengurus berhasil ditambahkan.';
        if ($modeAkun === 'baru') {
            $msg .= ' Akun login telah dibuat.';
        }

        return redirect()->route('pengurus.index')->with('success', $msg);
    }

    // ──────────────────────────────────────────────
    //  SHOW
    // ──────────────────────────────────────────────
    public function show(Pengurus $pengurus)
    {
        $pengurus->load(['pantiAsuhan', 'user']);
        return view('pages.pengurus.show', compact('pengurus'));
    }

    // ──────────────────────────────────────────────
    //  EDIT
    // ──────────────────────────────────────────────
    public function edit(Pengurus $pengurus)
    {
        $pantis = PantiAsuhan::aktif()->orderBy('nama_panti')->get();
        $pengurus->load('user');

        return view('pages.pengurus.edit', compact('pengurus', 'pantis'));
    }

    // ──────────────────────────────────────────────
    //  UPDATE
    // ──────────────────────────────────────────────
    public function update(Request $request, Pengurus $pengurus)
    {
        /*
         * mode_akun:
         *   'none'     → pengurus tanpa akun (lepas akun lama jika ada)
         *   'baru'     → buat user baru dan hubungkan
         *   'existing' → pengurus sudah punya akun, boleh ganti password
         */
        $modeAkun = $request->input('mode_akun', 'none');

        $rules = [
            'panti_asuhan_id'     => 'required|exists:panti_asuhan,id',
            'nama'                => 'required|string|max:50',
            'nik'                 => 'nullable|string|max:16',
            'jenis_kelamin'       => 'required|in:L,P',
            'tempat_lahir'        => 'nullable|string|max:100',
            'tanggal_lahir'       => 'nullable|date|before:today',
            'no_telp'             => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:100',
            'alamat'              => 'nullable|string',
            'jabatan'             => 'nullable|string|max:50',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'foto'                => 'nullable|image|max:2048',
            'status'              => 'required|in:aktif,nonaktif',
        ];

        if ($modeAkun === 'baru') {
            $rules['akun_name']                  = 'required|string|max:50';
            $rules['akun_email']                 = 'required|email|max:100|unique:users,email';
            $rules['akun_password']              = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        // Ganti password hanya jika diisi
        if ($modeAkun === 'existing' && $request->filled('akun_password')) {
            $rules['akun_password']              = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $modeAkun, $pengurus) {

            // ── Kelola akun ──────────────────────────
            if ($modeAkun === 'baru') {
                // Buat user baru
                $user = User::create([
                    'username'     => $validated['akun_name'],
                    'email'    => $validated['akun_email'],
                    'password' => Hash::make($validated['akun_password']),
                    'role'     => 'admin_panti',
                    'status'   => 'aktif',
                ]);
                $pengurus->user_id = $user->id;
            } elseif ($modeAkun === 'existing' && $pengurus->user_id) {
                // Ganti password jika diisi
                if ($request->filled('akun_password')) {
                    $pengurus->user->update([
                        'password' => Hash::make($validated['akun_password']),
                    ]);
                }
                // user_id tetap (tidak berubah)

            } elseif ($modeAkun === 'none') {
                // Lepas akun
                $pengurus->user_id = null;
            }

            // ── Foto ─────────────────────────────────
            if ($request->hasFile('foto')) {
                if ($pengurus->foto) {
                    Storage::disk('public')->delete($pengurus->foto);
                }
                $pengurus->foto = $request->file('foto')->store('pengurus', 'public');
            }

            // ── Data utama ────────────────────────────
            $pengurus->fill([
                'panti_asuhan_id'     => $validated['panti_asuhan_id'],
                'nama'                => $validated['nama'],
                'nik'                 => $validated['nik'] ?? null,
                'jenis_kelamin'       => $validated['jenis_kelamin'],
                'tempat_lahir'        => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir'       => $validated['tanggal_lahir'] ?? null,
                'no_telp'             => $validated['no_telp'] ?? null,
                'email'               => $validated['email'] ?? null,
                'alamat'              => $validated['alamat'] ?? null,
                'jabatan'             => $validated['jabatan'] ?? null,
                'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
                'status'              => $validated['status'],
            ])->save();
        });

        return redirect()->route('pengurus.show', $pengurus)
            ->with('success', 'Data pengurus berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────
    //  DESTROY
    // ──────────────────────────────────────────────
    public function destroy(Pengurus $pengurus)
    {
        if ($pengurus->foto) {
            Storage::disk('public')->delete($pengurus->foto);
        }

        $pengurus->delete();

        return redirect()->route('pengurus.index')
            ->with('success', 'Data pengurus berhasil dihapus.');
    }
}

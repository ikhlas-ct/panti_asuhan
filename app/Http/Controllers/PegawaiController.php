<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  INDEX                                                               */
    /* ------------------------------------------------------------------ */
    public function index(Request $request)
    {
        $query = Pegawai::with('user')->latest();

        // ── Filter pencarian ──────────────────────────────────────────
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('posisi', 'like', "%{$search}%")
                  ->orWhere('nohp', 'like', "%{$search}%");
            });
        }

        if ($posisi = $request->input('posisi')) {
            $query->where('posisi', $posisi);
        }

        $pegawais   = $query->paginate(10)->withQueryString();
        $totalPegawai = Pegawai::count();

        // Daftar posisi unik untuk filter
        $daftarPosisi = Pegawai::select('posisi')->distinct()->orderBy('posisi')->pluck('posisi');

        return view('pages.pegawai.index', compact('pegawais', 'totalPegawai', 'daftarPosisi'));
    }

    /* ------------------------------------------------------------------ */
    /*  CREATE                                                              */
    /* ------------------------------------------------------------------ */
    public function create()
    {
        // User yang belum terhubung ke data pegawai manapun (role admin_dinsos)
        $users = User::where('role', 'admin_dinsos')
                     ->whereDoesntHave('pegawai')
                     ->orderBy('username')
                     ->get();

        return view('pages.pegawai.create', compact('users'));
    }

    /* ------------------------------------------------------------------ */
    /*  STORE                                                               */
    /* ------------------------------------------------------------------ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'posisi'      => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'nohp'        => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'instagram'   => 'nullable|string|max:255',
            'twitter'     => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
            'foto_profil' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'id_user'     => 'nullable|exists:users,id',

            // Akun baru (opsional)
            'buat_akun'     => 'nullable|boolean',
            'akun_email'    => 'nullable|required_if:buat_akun,1|email|unique:users,email',
            'akun_password' => 'nullable|required_if:buat_akun,1|string|min:8',
        ], [
            'nama.required'        => 'Nama pegawai wajib diisi.',
            'posisi.required'      => 'Posisi / jabatan wajib diisi.',
            'foto_profil.required' => 'Foto profil wajib diunggah.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        // ── Simpan foto ───────────────────────────────────────────────
        $fotoPath = $request->file('foto_profil')->store('pegawai/foto', 'public');

        // ── Buat akun user jika diminta ───────────────────────────────
        $idUser = $validated['id_user'] ?? null;

        if ($request->boolean('buat_akun') && $request->filled('akun_email')) {
            $user = User::create([
                'username' => $validated['nama'],
                'email'    => $validated['akun_email'],
                'password' => Hash::make($validated['akun_password']),
                'role'     => 'admin_dinsos',
                'status'   => 'aktif',
            ]);
            $idUser = $user->id;
        }

        Pegawai::create([
            'id_user'     => $idUser,
            'nama'        => $validated['nama'],
            'posisi'      => $validated['posisi'],
            'email'       => $validated['email'],
            'nohp'        => $validated['nohp'],
            'alamat'      => $validated['alamat'],
            'deskripsi'   => $validated['deskripsi'],
            'instagram'   => $validated['instagram'],
            'twitter'     => $validated['twitter'],
            'facebook'    => $validated['facebook'],
            'foto_profil' => $fotoPath,
        ]);

        return redirect()->route('pegawai.index')
                         ->with('success', "Data pegawai {$validated['nama']} berhasil ditambahkan.");
    }

    /* ------------------------------------------------------------------ */
    /*  SHOW                                                                */
    /* ------------------------------------------------------------------ */
    public function show(Pegawai $pegawai)
    {
        $pegawai->load('user');
        return view('pages.pegawai.show', compact('pegawai'));
    }

    /* ------------------------------------------------------------------ */
    /*  EDIT                                                                */
    /* ------------------------------------------------------------------ */
    public function edit(Pegawai $pegawai)
    {
        $pegawai->load('user');

        // User admin_dinsos yang belum punya pegawai, ATAU user milik pegawai ini sendiri
        $users = User::where('role', 'admin_dinsos')
                     ->where(function ($q) use ($pegawai) {
                         $q->whereDoesntHave('pegawai')
                           ->orWhere('id', $pegawai->id_user);
                     })
                     ->orderBy('username')
                     ->get();

        return view('pages.pegawai.edit', compact('pegawai', 'users'));
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                              */
    /* ------------------------------------------------------------------ */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'posisi'      => 'required|string|max:255',
            'email'       => 'nullable|email|max:255',
            'nohp'        => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'instagram'   => 'nullable|string|max:255',
            'twitter'     => 'nullable|string|max:255',
            'facebook'    => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'id_user'     => 'nullable|exists:users,id',
        ], [
            'nama.required'   => 'Nama pegawai wajib diisi.',
            'posisi.required' => 'Posisi / jabatan wajib diisi.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        // ── Update foto jika ada yang baru ────────────────────────────
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('pegawai/foto', 'public');
        } else {
            unset($validated['foto_profil']); // pertahankan foto lama
        }

        $pegawai->update($validated);

        return redirect()->route('pegawai.show', $pegawai)
                         ->with('success', "Data pegawai {$pegawai->nama} berhasil diperbarui.");
    }

    /* ------------------------------------------------------------------ */
    /*  DESTROY                                                             */
    /* ------------------------------------------------------------------ */
    public function destroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;

        // Hapus foto dari storage
        if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
            Storage::disk('public')->delete($pegawai->foto_profil);
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')
                         ->with('success', "Data pegawai {$nama} berhasil dihapus.");
    }
}

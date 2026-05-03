<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  SHOW – tampilkan profil pegawai milik user yang sedang login       */
    /* ------------------------------------------------------------------ */
    public function show()
    {
        $user    = Auth::user()->load('pegawai');
        $pegawai = $user->pegawai;

        // Redirect jika user ini belum punya profil pegawai
        if (! $pegawai) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil pegawai Anda belum dibuat. Hubungi administrator.');
        }

        return view('pages.pegawai.profil', compact('user', 'pegawai'));
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE – simpan perubahan data diri                                */
    /* ------------------------------------------------------------------ */
    public function update(Request $request)
    {
        $user    = Auth::user();
        $pegawai = $user->pegawai;

        if (! $pegawai) {
            return redirect()->route('pegawai.profil')
                ->with('error', 'Profil pegawai tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'posisi'    => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'nohp'      => 'nullable|string|max:20',
            'alamat'    => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'instagram' => 'nullable|url|max:255',
            'twitter'   => 'nullable|url|max:255',
            'facebook'  => 'nullable|url|max:255',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'posisi.required' => 'Posisi wajib diisi.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        // ── Update foto jika ada ──────────────────────────────────────
        if ($request->hasFile('foto_profil')) {
            if ($pegawai->foto_profil && Storage::disk('public')->exists($pegawai->foto_profil)) {
                Storage::disk('public')->delete($pegawai->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')
                ->store('pegawai/foto', 'public');
        } else {
            unset($validated['foto_profil']);
        }

        $pegawai->update($validated);

        // Sinkronkan nama ke tabel users juga
        $user->update(['name' => $validated['nama']]);

        return redirect()->route('pegawai.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE PASSWORD                                                     */
    /* ------------------------------------------------------------------ */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'          => 'required|string',
            'password'                  => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password.min'              => 'Password minimal 8 karakter.',
        ]);

        $user = Auth::user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput()
                ->with('tab', 'password');
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('pegawai.profil')
            ->with('success', 'Password berhasil diubah. Silakan login kembali.')
            ->with('tab', 'password');
    }
}

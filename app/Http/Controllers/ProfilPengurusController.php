<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class ProfilPengurusController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $pengurus = $user->pengurus()->with('pantiAsuhan')->firstOrFail();

        return view('pages.pengurus.profil', compact('user', 'pengurus'));
    }

    // ── Update data diri ─────────────────────────────────────────
    public function update(Request $request)
    {
        $user     = Auth::user();
        $pengurus = $user->pengurus()->firstOrFail();

        $validated = $request->validate([
            'username'            => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id)
            ],
            'email'               => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'nama'                => ['required', 'string', 'max:100'],
            'nik'                 => [
                'required',
                'digits:16',
                Rule::unique('pengurus', 'nik')->ignore($pengurus->id)
            ],
            'jenis_kelamin'       => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir'        => ['required', 'string', 'max:100'],
            'tanggal_lahir'       => ['required', 'date', 'before:today'],
            'no_telp'             => ['required', 'string', 'max:20'],
            'alamat'              => ['required', 'string', 'max:500'],
            'jabatan'             => ['required', 'string', 'max:100'],
            'pendidikan_terakhir' => ['required', 'string', 'max:50'],
            'foto'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nik.digits'           => 'NIK harus 16 digit angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'foto.max'             => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email'    => $validated['email'],
        ]);

        $fotoPath = $pengurus->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('pengurus/foto', 'public');
        }

        $pengurus->update([
            'nama'                => $validated['nama'],
            'nik'                 => $validated['nik'],
            'jenis_kelamin'       => $validated['jenis_kelamin'],
            'tempat_lahir'        => $validated['tempat_lahir'],
            'tanggal_lahir'       => $validated['tanggal_lahir'],
            'no_telp'             => $validated['no_telp'],
            'alamat'              => $validated['alamat'],
            'jabatan'             => $validated['jabatan'],
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'],
            'foto'                => $fotoPath,
        ]);

        return redirect()->route('admin_panti.profil')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('tab', 'edit');
    }

    // ── Update password ──────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin_panti.profil')
            ->with('success', 'Password berhasil diperbarui.')
            ->with('tab', 'password');
    }

    // ── Quick upload foto dari hero ───────────────────────────────
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user     = Auth::user();
        $pengurus = $user->pengurus()->firstOrFail();

        if ($pengurus->foto && Storage::disk('public')->exists($pengurus->foto)) {
            Storage::disk('public')->delete($pengurus->foto);
        }

        $pengurus->update([
            'foto' => $request->file('foto')->store('pengurus/foto', 'public'),
        ]);

        return redirect()->route('admin_panti.profil')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
}

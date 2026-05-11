<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilDonaturController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $donatur = $user->donatur()->withCount('donasi')->firstOrFail();

        return view('donatur.profil', compact('user', 'donatur'));
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $donatur = $user->donatur()->firstOrFail();

        $validated = $request->validate([
            'username'      => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id)
            ],
            'email'         => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'nama'          => ['required', 'string', 'max:50'],
            'jenis_donatur' => ['required', Rule::in(['perorangan', 'organisasi', 'perusahaan', 'pemerintah'])],
            'no_telp'       => ['nullable', 'string', 'max:20'],
            'alamat'        => ['nullable', 'string', 'max:500'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
            'foto'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user->update([
            'username' => $validated['username'],
            'email'    => $validated['email'],
        ]);

        $fotoPath = $donatur->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('donatur/foto', 'public');
        }

        $donatur->update([
            'nama'          => $validated['nama'],
            'jenis_donatur' => $validated['jenis_donatur'],
            'no_telp'       => $validated['no_telp'],
            'alamat'        => $validated['alamat'],
            'keterangan'    => $validated['keterangan'],
            'foto'          => $fotoPath,
        ]);

        return redirect()->route('donatur.profil')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('tab', 'edit');
    }

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
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('donatur.profil')
            ->with('success', 'Password berhasil diperbarui.')
            ->with('tab', 'password');
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user    = Auth::user();
        $donatur = $user->donatur()->firstOrFail();

        if ($donatur->foto && Storage::disk('public')->exists($donatur->foto)) {
            Storage::disk('public')->delete($donatur->foto);
        }

        $donatur->update([
            'foto' => $request->file('foto')->store('donatur/foto', 'public'),
        ]);

        return redirect()->route('donatur.profil')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
}

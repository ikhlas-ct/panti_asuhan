<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfilpegawaiController extends Controller
{
    public function profil()
    {
        $user = Auth::user();
        $profil = $user->pegawai;

        return view('pages.pegawai.profil', compact('profil'));
    }

    public function profil_update(Request $request)
    {
        $user = Auth::user();
        $profil = Pegawai::where('id_user', $user->id)->firstOrFail();

        // Validasi input - hanya field yang ada di migration & model fillable
        $request->validate([
            'nama'              => 'required|string|max:255',
            'posisi'            => 'nullable|string|max:255',
            'alamat'            => 'nullable|string|max:255',
            'nohp'              => 'nullable|string|max:15',
            'email'             => 'nullable|email|max:255',
            'deskripsi'         => 'nullable|string|max:500',
            'instagram'         => 'nullable|string|max:255',
            'twitter'           => 'nullable|string|max:255',
            'facebook'          => 'nullable|string|max:255',
            'foto_profil'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data yang akan diupdate - sesuai field di migration
        $data = $request->only([
            'nama',
            'posisi',
            'alamat',
            'nohp',
            'email',
            'deskripsi',
            'instagram',
            'twitter',
            'facebook'
        ]);

        // Upload foto profil baru jika ada
        if ($request->hasFile('foto_profil')) {
            if ($profil->foto_profil && file_exists(public_path($profil->foto_profil))) {
                unlink(public_path($profil->foto_profil));
            }
            $filename = time() . '_' . Str::slug($request->file('foto_profil')->getClientOriginalName());
            $request->file('foto_profil')->move(public_path('uploads/profil'), $filename);
            $data['foto_profil'] = 'uploads/profil/' . $filename;
        }

        // Update data profil pegawai
        $profil->update($data);

        // Hapus bagian update NIP karena 'nip' dan 'nik' tidak ada di migration/model
        // Jika diperlukan nanti, tambahkan kolom dulu di migration

        return redirect()->route('pegawai.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function password_update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai'])->withInput();
        }

        // Cek password baru tidak boleh sama dengan lama
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama'])->withInput();
        }

        // Update password (hapus duplikat update di luar if)
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('pegawai.profil')->with('success', 'Password berhasil diperbarui!');
    }
}

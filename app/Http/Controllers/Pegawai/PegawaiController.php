<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with('user')->paginate(15);
        return view('pages.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        return view('pages.pegawai.create');
    }

    public function store(Request $request)
    {
        $pegawaiData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userData = $request->validate([
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto_profil')) {
            $filename = time() . '_' . Str::slug($request->file('foto_profil')->getClientOriginalName());
            $request->file('foto_profil')->move(public_path('uploads/pegawai'), $filename);
            $pegawaiData['foto_profil'] = 'uploads/pegawai/' . $filename;
        }

        $pegawaiData['id_user'] = null;

        // Buat User baru jika fields User diisi
        if ($request->filled('username') && $request->filled('password')) {
            $user = User::create([
                'username' => $userData['username'],
                'password' => Hash::make($userData['password']),
                'status' => $userData['status'] ?? 'active', // Default status jika tidak diisi
            ]);
            $pegawaiData['id_user'] = $user->id;
        }

        Pegawai::create($pegawaiData);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dibuat.');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);
        return view('pages.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $pegawaiData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nohp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userData = $request->validate([
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($pegawai->foto_profil && file_exists(public_path($pegawai->foto_profil))) {
                unlink(public_path($pegawai->foto_profil));
            }
            $filename = time() . '_' . Str::slug($request->file('foto_profil')->getClientOriginalName());
            $request->file('foto_profil')->move(public_path('uploads/pegawai'), $filename);
            $pegawaiData['foto_profil'] = 'uploads/pegawai/' . $filename;
        }

        // Handle User
        $user = $pegawai->user;
        if ($user) {
            // Update User jika ada perubahan
            if ($request->filled('username')) {
                $user->username = $userData['username'];
            }
            if ($request->filled('password')) {
                $user->password = Hash::make($userData['password']);
            }
            if ($request->filled('status')) {
                $user->status = $userData['status'];
            }
            $user->save();
        } else {
            // Buat User baru jika fields diisi dan belum ada User
            if ($request->filled('username') && $request->filled('password')) {
                $newUser = User::create([
                    'username' => $userData['username'],
                    'password' => Hash::make($userData['password']),
                    'status' => $userData['status'] ?? 'active',
                ]);
                $pegawaiData['id_user'] = $newUser->id;
            }
        }

        $pegawai->update($pegawaiData);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        if ($pegawai->foto_profil && file_exists(public_path($pegawai->foto_profil))) {
            unlink(public_path($pegawai->foto_profil));
        }
        // Note: User tidak dihapus, hanya unlink id_user jika diperlukan
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}

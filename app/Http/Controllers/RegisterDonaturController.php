<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterDonaturController extends Controller
{
    /**
     * Tampilkan form registrasi donatur.
     */
    public function create()
    {
        return view('pages.login.register');
    }

    /**
     * Proses registrasi donatur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => ['required', 'string', 'max:50'],
            'jenis_donatur' => ['required', 'in:perorangan,organisasi,perusahaan,pemerintah'],
            'username'      => ['required', 'string', 'max:100', 'unique:users,username'],
            'email'         => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nama.required'          => 'Nama wajib diisi.',
            'nama.max'               => 'Nama maksimal 50 karakter.',
            'jenis_donatur.required' => 'Jenis donatur wajib dipilih.',
            'jenis_donatur.in'       => 'Jenis donatur tidak valid.',
            'username.required'      => 'Username wajib diisi.',
            'username.max'           => 'Username maksimal 100 karakter.',
            'username.unique'        => 'Username sudah digunakan.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat akun user
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'donatur',
                'status'   => 'aktif',
            ]);

            // 2. Buat profil donatur yang terhubung ke user
            Donatur::create([
                'user_id'       => $user->id,
                'nama'          => $request->nama,
                'jenis_donatur' => $request->jenis_donatur,
                'status'        => 'aktif',
            ]);
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan masuk dengan akun Anda.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DonaturController extends Controller
{
    // ──────────────────────────────────────────────
    //  INDEX
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Donatur::with('user');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama',   'like', "%{$q}%")
                    ->orWhere('no_telp', 'like', "%{$q}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_donatur', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('akun')) {
            if ($request->akun === 'ya') {
                $query->whereNotNull('user_id');
            } elseif ($request->akun === 'tidak') {
                $query->whereNull('user_id');
            }
        }

        $donaturs       = $query->latest()->paginate(12)->withQueryString();
        $totalAktif     = Donatur::where('status', 'aktif')->count();
        $totalNonaktif  = Donatur::where('status', 'nonaktif')->count();
        $totalPunyaAkun = Donatur::whereNotNull('user_id')->count();
        $totalDonatur   = Donatur::count();

        return view('pages.donatur.index', compact(
            'donaturs',
            'totalAktif',
            'totalNonaktif',
            'totalPunyaAkun',
            'totalDonatur'
        ));
    }

    // ──────────────────────────────────────────────
    //  CREATE
    // ──────────────────────────────────────────────
    public function create()
    {
        return view('pages.donatur.create');
    }

    // ──────────────────────────────────────────────
    //  STORE
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        /*
         * mode_akun:
         *   'none'  → simpan donatur tanpa akun
         *   'baru'  → buat user baru (role: donatur) sekaligus
         */
        $modeAkun = $request->input('mode_akun', 'none');

        $rules = [
            'nama'          => 'required|string|max:50',
            'jenis_donatur' => 'required|in:perorangan,organisasi,perusahaan,pemerintah',
            'no_telp'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|max:2048',
            'status'        => 'required|in:aktif,nonaktif',
            'keterangan'    => 'nullable|string',
        ];

        if ($modeAkun === 'baru') {
            $rules['akun_username']              = 'required|string|max:100';
            $rules['akun_email']                 = 'required|email|max:100|unique:users,email';
            $rules['akun_password']              = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $modeAkun) {
            $userId = null;

            if ($modeAkun === 'baru') {
                $user   = User::create([
                    'username' => $validated['akun_username'],
                    'email'    => $validated['akun_email'],
                    'password' => Hash::make($validated['akun_password']),
                    'role'     => 'donatur',
                    'status'   => 'aktif',
                ]);
                $userId = $user->id;
            }

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('donatur', 'public');
            }

            Donatur::create([
                'user_id'       => $userId,
                'nama'          => $validated['nama'],
                'jenis_donatur' => $validated['jenis_donatur'],
                'no_telp'       => $validated['no_telp'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
                'foto'          => $fotoPath,
                'status'        => $validated['status'],
                'keterangan'    => $validated['keterangan'] ?? null,
            ]);
        });

        $msg = 'Data donatur berhasil ditambahkan.';
        if ($modeAkun === 'baru') $msg .= ' Akun login telah dibuat.';

        return redirect()->route('donatur.index')->with('success', $msg);
    }

    // ──────────────────────────────────────────────
    //  SHOW
    // ──────────────────────────────────────────────
    public function show(Donatur $donatur)
    {
        $donatur->load('user');

        $donasis = $donatur->donasi()
            ->with(['pantiAsuhan', 'dikonfirmasiOleh'])
            ->latest('tanggal_donasi')
            ->paginate(8, ['*'], 'donasi_page');

        $totalDonasi     = $donatur->donasi()->count();
        $totalDiterima   = $donatur->donasi()->where('status', 'diterima')->count();
        $totalNominal    = $donatur->donasi()
            ->where('status', 'diterima')
            ->where('jenis_donasi', 'uang')
            ->sum('nominal');
        $totalBarang     = $donatur->donasi()
            ->where('status', 'diterima')
            ->where('jenis_donasi', 'barang')
            ->count();

        return view('pages.donatur.show', compact(
            'donatur',
            'donasis',
            'totalDonasi',
            'totalDiterima',
            'totalNominal',
            'totalBarang'
        ));
    }

    // ──────────────────────────────────────────────
    //  EDIT
    // ──────────────────────────────────────────────
    public function edit(Donatur $donatur)
    {
        $donatur->load('user');
        return view('pages.donatur.edit', compact('donatur'));
    }

    // ──────────────────────────────────────────────
    //  UPDATE
    // ──────────────────────────────────────────────
    public function update(Request $request, Donatur $donatur)
    {
        /*
         * mode_akun:
         *   'none'     → tanpa akun (lepas akun lama jika ada)
         *   'baru'     → buat akun baru
         *   'existing' → sudah punya akun, boleh ganti password
         */
        $modeAkun = $request->input('mode_akun', 'none');

        $rules = [
            'nama'          => 'required|string|max:50',
            'jenis_donatur' => 'required|in:perorangan,organisasi,perusahaan,pemerintah',
            'no_telp'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|max:2048',
            'status'        => 'required|in:aktif,nonaktif',
            'keterangan'    => 'nullable|string',
        ];

        if ($modeAkun === 'baru') {
            $rules['akun_username']              = 'required|string|max:100';
            $rules['akun_email']                 = 'required|email|max:100|unique:users,email';
            $rules['akun_password']              = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        if ($modeAkun === 'existing' && $request->filled('akun_password')) {
            $rules['akun_password']              = ['required', 'confirmed', Password::min(8)];
            $rules['akun_password_confirmation'] = 'required';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $validated, $modeAkun, $donatur) {

            if ($modeAkun === 'baru') {
                $user = User::create([
                    'username' => $validated['akun_username'],
                    'email'    => $validated['akun_email'],
                    'password' => Hash::make($validated['akun_password']),
                    'role'     => 'donatur',
                    'status'   => 'aktif',
                ]);
                $donatur->user_id = $user->id;
            } elseif ($modeAkun === 'existing' && $donatur->user_id) {
                if ($request->filled('akun_password')) {
                    $donatur->user->update([
                        'password' => Hash::make($validated['akun_password']),
                    ]);
                }
            } elseif ($modeAkun === 'none') {
                $donatur->user_id = null;
            }

            if ($request->hasFile('foto')) {
                if ($donatur->foto) {
                    Storage::disk('public')->delete($donatur->foto);
                }
                $donatur->foto = $request->file('foto')->store('donatur', 'public');
            }

            $donatur->fill([
                'nama'          => $validated['nama'],
                'jenis_donatur' => $validated['jenis_donatur'],
                'no_telp'       => $validated['no_telp'] ?? null,
                'alamat'        => $validated['alamat'] ?? null,
                'status'        => $validated['status'],
                'keterangan'    => $validated['keterangan'] ?? null,
            ])->save();
        });

        return redirect()->route('donatur.show', $donatur)
            ->with('success', 'Data donatur berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────
    //  DESTROY
    // ──────────────────────────────────────────────
    public function destroy(Donatur $donatur)
    {
        if ($donatur->foto) {
            Storage::disk('public')->delete($donatur->foto);
        }

        $donatur->delete();

        return redirect()->route('pages.donatur.index')
            ->with('success', 'Data donatur berhasil dihapus.');
    }
}

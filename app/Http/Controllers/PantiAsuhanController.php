<?php

namespace App\Http\Controllers;

use App\Models\FotoPanti;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PantiAsuhanController extends Controller
{
    // ----------------------------------------------------------------
    // INDEX
    // ----------------------------------------------------------------
    public function index(Request $request)
    {
        $query = PantiAsuhan::withCount('anakAsuh');

        // Filter pencarian
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_panti', 'like', "%{$search}%")
                  ->orWhere('alamat',    'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%")
                  ->orWhere('kelurahan', 'like', "%{$search}%");
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $pantis = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'    => PantiAsuhan::count(),
            'aktif'    => PantiAsuhan::where('status', 'aktif')->count(),
            'nonaktif' => PantiAsuhan::where('status', 'nonaktif')->count(),
        ];

        return view('pages.panti_asuhan.index', compact('pantis', 'stats'));
    }

    // ----------------------------------------------------------------
    // CREATE
    // ----------------------------------------------------------------
    public function create()
    {
        return view('pages.panti_asuhan.create');
    }

    // ----------------------------------------------------------------
    // STORE
    // ----------------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_panti'   => 'required|string|max:50',
            'alamat'       => 'required|string|max:100',
            'kelurahan'    => 'nullable|string|max:50',
            'kecamatan'    => 'nullable|string|max:50',
            'no_telp'      => 'nullable|string|max:20',
            'nama_kontak'  => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:100',
            'status'       => 'required|in:aktif,nonaktif',
            'keterangan'   => 'nullable|string',
            // Foto panti (multiple)
            'fotos'        => 'nullable|array',
            'fotos.*' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_ket'     => 'nullable|array',
            'foto_ket.*'   => 'nullable|string|max:100',
        ]);

        $panti = PantiAsuhan::create([
            'nama_panti'   => $validated['nama_panti'],
            'alamat'       => $validated['alamat'],
            'kelurahan'    => $validated['kelurahan']   ?? null,
            'kecamatan'    => $validated['kecamatan']   ?? null,
            'no_telp'      => $validated['no_telp']     ?? null,
            'nama_kontak'  => $validated['nama_kontak'] ?? null,
            'email'        => $validated['email']       ?? null,
            'status'       => $validated['status'],
            'keterangan'   => $validated['keterangan']  ?? null,
        ]);

        // Simpan foto-foto panti
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $i => $file) {
                $path = $file->store('foto-panti', 'public');
                FotoPanti::create([
                    'panti_asuhan_id' => $panti->id,
                    'foto'            => $path,
                    'keterangan'      => $request->input("foto_ket.{$i}"),
                    'urutan'          => $i,
                ]);
            }
        }

        return redirect()->route('panti-asuhan.index')
            ->with('success', "Panti asuhan \"{$panti->nama_panti}\" berhasil ditambahkan.");
    }

    // ----------------------------------------------------------------
    // SHOW
    // ----------------------------------------------------------------
    public function show(PantiAsuhan $pantiAsuhan)
    {
        $pantiAsuhan->load(['fotoPanti' => fn ($q) => $q->orderBy('urutan')]);

        return view('pages.panti_asuhan.show', compact('pantiAsuhan'));
    }

    // ----------------------------------------------------------------
    // EDIT
    // ----------------------------------------------------------------
    public function edit(PantiAsuhan $pantiAsuhan)
    {
        $pantiAsuhan->load(['fotoPanti' => fn ($q) => $q->orderBy('urutan')]);

        return view('pages.panti_asuhan.edit', compact('pantiAsuhan'));
    }

    // ----------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------
    public function update(Request $request, PantiAsuhan $pantiAsuhan)
    {
        $validated = $request->validate([
            'nama_panti'   => 'required|string|max:50',
            'alamat'       => 'required|string|max:100',
            'kelurahan'    => 'nullable|string|max:50',
            'kecamatan'    => 'nullable|string|max:50',
            'no_telp'      => 'nullable|string|max:20',
            'nama_kontak'  => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:100',
            'status'       => 'required|in:aktif,nonaktif',
            'keterangan'   => 'nullable|string',
            // Foto baru (tambahan)
            'fotos'        => 'nullable|array',
            'fotos.*'      => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foto_ket'     => 'nullable|array',
            'foto_ket.*'   => 'nullable|string|max:100',
        ]);

        $pantiAsuhan->update([
            'nama_panti'   => $validated['nama_panti'],
            'alamat'       => $validated['alamat'],
            'kelurahan'    => $validated['kelurahan']   ?? null,
            'kecamatan'    => $validated['kecamatan']   ?? null,
            'no_telp'      => $validated['no_telp']     ?? null,
            'nama_kontak'  => $validated['nama_kontak'] ?? null,
            'email'        => $validated['email']       ?? null,
            'status'       => $validated['status'],
            'keterangan'   => $validated['keterangan']  ?? null,
        ]);

        // Tambah foto baru
        if ($request->hasFile('fotos')) {
            $urutanStart = $pantiAsuhan->fotoPanti()->max('urutan') + 1;
            foreach ($request->file('fotos') as $i => $file) {
                $path = $file->store('foto-panti', 'public');
                FotoPanti::create([
                    'panti_asuhan_id' => $pantiAsuhan->id,
                    'foto'            => $path,
                    'keterangan'      => $request->input("foto_ket.{$i}"),
                    'urutan'          => $urutanStart + $i,
                ]);
            }
        }

        return redirect()->route('panti-asuhan.show', $pantiAsuhan)
            ->with('success', "Data panti asuhan \"{$pantiAsuhan->nama_panti}\" berhasil diperbarui.");
    }

    // ----------------------------------------------------------------
    // DESTROY
    // ----------------------------------------------------------------
    public function destroy(PantiAsuhan $pantiAsuhan)
    {
        // Hapus semua foto dari storage
        foreach ($pantiAsuhan->fotoPanti as $foto) {
            Storage::disk('public')->delete($foto->foto);
        }

        $nama = $pantiAsuhan->nama_panti;
        $pantiAsuhan->delete(); // cascade akan hapus foto_panti rows

        return redirect()->route('panti-asuhan.index')
            ->with('success', "Panti asuhan \"{$nama}\" berhasil dihapus.");
    }

    // ----------------------------------------------------------------
    // DESTROY FOTO (individual)
    // ----------------------------------------------------------------
    public function destroyFoto(PantiAsuhan $pantiAsuhan, FotoPanti $foto)
    {
        // Pastikan foto milik panti ini
        abort_if($foto->panti_asuhan_id !== $pantiAsuhan->id, 403);

        Storage::disk('public')->delete($foto->foto);
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}

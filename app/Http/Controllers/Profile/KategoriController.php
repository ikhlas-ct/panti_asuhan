<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Str;


class KategoriController extends Controller
{
    // Tampilkan daftar kategori
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kategoris = Kategori::when($search, function ($query, $search) {
            $query->where('nama_kategori', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        })
            ->orderBy('nama_kategori', 'asc')
            ->paginate(15)
            ->appends(['search' => $search]);   // Penting agar pagination tetap membawa parameter search

        return view('pages.camat.konten.kategori', compact('kategoris', 'search'));
    }
    // Simpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategori,nama_kategori|string|max:255',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Buat slug unik
        $slug = Str::slug($request->nama_kategori);
        $slugCount = Kategori::where('slug', 'like', $slug . '%')->count();
        if ($slugCount) {
            $slug .= '-' . ($slugCount + 1);
        }

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $slug,
            'icon' => $request->icon,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Tampilkan form edit (jika ingin pakai halaman terpisah)
    public function edit($id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);
        return view('kategori.edit', compact('kategori'));
    }

    // Update data kategori
    public function update(Request $request, $id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id_kategori . ',id_kategori',
            'icon' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Jika nama_kategori berubah, generate slug baru
        $slug = $kategori->slug; // default tetap slug lama
        if ($kategori->nama_kategori !== $request->nama_kategori) {
            $slug = Str::slug($request->nama_kategori);
            $slugCount = Kategori::where('slug', 'like', $slug . '%')
                ->where('id_kategori', '!=', $id_kategori)
                ->count();
            if ($slugCount) {
                $slug .= '-' . ($slugCount + 1);
            }
        }

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $slug,
            'icon' => $request->icon,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // Hapus data kategori
    public function destroy($id_kategori)
    {
        $kategori = Kategori::findOrFail($id_kategori);
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(12);
        return view('pages.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('pages.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'       => 'required|image|max:10000',
            'title'       => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url'  => 'nullable|url|max:191',
        ]);

        $image = $request->file('image');

        // Buat folder jika belum ada
        if (!file_exists(public_path('galleries'))) {
            mkdir(public_path('galleries'), 0755, true);
        }

        $filename = $image->hashName();                    // nama unik + extension
        $image->move(public_path('galleries'), $filename);

        $validated['image'] = 'galleries/' . $filename;

        Gallery::create($validated);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery berhasil dibuat.');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('pages.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'image'       => 'nullable|image|max:10000',
            'title'       => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url'  => 'nullable|url|max:191',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if (!file_exists(public_path('galleries'))) {
                mkdir(public_path('galleries'), 0755, true);
            }

            $filename = $image->hashName();
            $image->move(public_path('galleries'), $filename);

            // Hapus gambar lama
            if ($gallery->image && file_exists(public_path($gallery->image))) {
                unlink(public_path($gallery->image));
            }

            $validated['image'] = 'galleries/' . $filename;
        }

        $gallery->update($validated);

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return redirect()->route('gallery.index')
            ->with('success', 'Gallery berhasil dihapus.');
    }
}

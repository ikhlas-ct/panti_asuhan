<?php

namespace App\Http\Controllers\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'required|image|max:10000',
            'title' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:191',
        ]);

        $path = $request->file('image')->store('galleries', 'public');

        Gallery::create(array_merge($validated, ['image' => $path]));

        return redirect()->route('gallery.index')->with('success', 'Gallery berhasil dibuat.');
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
            'image' => 'nullable|image|max:10000',
            'title' => 'nullable|string|max:191',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|url|max:191',
        ]);

        if ($request->hasFile('image')) {
            // delete old image
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $path = $request->file('image')->store('galleries', 'public');
            $validated['image'] = $path;
        }

        $gallery->update($validated);

        return redirect()->route('gallery.index')->with('success', 'Gallery berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();
        return redirect()->route('gallery.index')->with('success', 'Gallery berhasil dihapus.');
    }
}



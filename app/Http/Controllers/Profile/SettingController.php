<?php

namespace App\Http\Controllers\Profile;

use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = WebsiteSetting::firstOrCreate([]);
        return view('pages.camat.profil.setting', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama'              => 'nullable|string|max:255',
            'alamat'            => 'nullable|string',
            'email'             => 'nullable|email|max:255',
            'nomor_telepon'     => 'nullable|string|max:20', 
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slogan'            => 'nullable|string|max:255',
            'social_facebook'   => 'nullable|url|max:255',
            'social_instagram'  => 'nullable|url|max:255',
            'social_twitter'    => 'nullable|url|max:255',
            'social_youtube'    => 'nullable|url|max:255',
        ]);

        $settings = WebsiteSetting::firstOrCreate([]);

        // Handle upload logo
        if ($request->hasFile('logo')) {
            if ($settings->logo && file_exists(public_path($settings->logo))) {
                unlink(public_path($settings->logo));
            }
            $filename = time() . '_' . Str::slug($request->file('logo')->getClientOriginalName());
            $request->file('logo')->move(public_path('uploads/logos'), $filename);
            $validated['logo'] = 'uploads/logos/' . $filename;
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->route('camat.settings.edit')
            ->with('success', 'Pengaturan website berhasil diperbarui!');
    }

    public function pengantar()
    {
        $settings = WebsiteSetting::firstOrCreate([]);
        return view('pages.camat.profil.kata_pengantar', compact('settings'));
    }

    public function pengantar_update(Request $request)
    {
        $validated = $request->validate([
            'title_pengantar'     => 'nullable|string|max:255',
            'paragraf_pengantar'  => 'nullable|string',
            'gambar_pengantar'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $settings = WebsiteSetting::firstOrCreate([]);

        if ($request->hasFile('gambar_pengantar')) {
            if ($settings->gambar_pengantar && file_exists(public_path($settings->gambar_pengantar))) {
                unlink(public_path($settings->gambar_pengantar));
            }
            $filename = time() . '_' . Str::slug($request->file('gambar_pengantar')->getClientOriginalName());
            $request->file('gambar_pengantar')->move(public_path('uploads/settings'), $filename);
            $validated['gambar_pengantar'] = 'uploads/settings/' . $filename;
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Pengaturan kata pengantar berhasil diperbarui.');
    }
}

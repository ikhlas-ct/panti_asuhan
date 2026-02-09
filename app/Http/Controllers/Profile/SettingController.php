<?php

namespace App\Http\Controllers\Profile;

use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
            'nomor_telepon'     => 'nullable|string|max:20', // bisa ditambah regex jika ingin format nomor HP
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
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
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
            if ($settings->gambar_pengantar && Storage::disk('public')->exists($settings->gambar_pengantar)) {
                Storage::disk('public')->delete($settings->gambar_pengantar);
            }
            $validated['gambar_pengantar'] = $request->file('gambar_pengantar')->store('settings', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'Pengaturan kata pengantar berhasil diperbarui.');
    }
}

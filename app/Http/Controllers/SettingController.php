<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_website' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $setting = Setting::updateOrCreate(
            ['id' => 1],
            ['nama_website' => $request->nama_website]
        );

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::delete('public/' . $setting->logo);
            }
            
            $path = $request->file('logo')->store('images', 'public');
            $setting->logo = $path;
            $setting->save();
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
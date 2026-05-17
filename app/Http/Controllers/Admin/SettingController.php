<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'logo_url']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        if ($request->hasFile('logo_url')) {
            $file = $request->file('logo_url');
            $path = $file->store('settings', 'public');
            
            // Delete old logo if exists
            $oldLogo = Setting::where('key', 'logo_url')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            Setting::updateOrCreate(
                ['key' => 'logo_url'],
                ['value' => $path, 'type' => 'image']
            );
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::store(),
            'logoUrl' => Setting::logoUrl(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:120'],
            'store_tagline' => ['required', 'string', 'max:220'],
            'header_subtitle' => ['nullable', 'string', 'max:160'],
            'whatsapp_number' => ['required', 'string', 'max:30'],
            'support_email' => ['nullable', 'email', 'max:120'],
            'business_address' => ['nullable', 'string', 'max:300'],
            'guide_title' => ['required', 'string', 'max:160'],
            'guide_subtitle' => ['nullable', 'string', 'max:300'],
            'qna_title' => ['required', 'string', 'max:160'],
            'qna_subtitle' => ['nullable', 'string', 'max:300'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $currentLogo = Setting::getValue('logo_path');
        $logoPath = $currentLogo;

        if ($request->boolean('remove_logo')) {
            $this->deletePublicFile($logoPath);
            $logoPath = '';
        }

        if ($request->hasFile('logo')) {
            $this->deletePublicFile($logoPath);
            $logoPath = $request->file('logo')->store('settings', 'public');
        }

        foreach ([
            'store_name', 'store_tagline', 'header_subtitle', 'whatsapp_number',
            'support_email', 'business_address', 'guide_title', 'guide_subtitle',
            'qna_title', 'qna_subtitle',
        ] as $key) {
            Setting::putValue($key, trim((string) ($data[$key] ?? '')));
        }

        Setting::putValue('logo_path', $logoPath);

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && ! str_starts_with($path, '/') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

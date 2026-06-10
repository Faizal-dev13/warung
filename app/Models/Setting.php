<?php

namespace App\Models;

use App\Models\Concerns\ClearsPublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class Setting extends Model
{
    use ClearsPublicCache;
    use HasFactory;

    protected $fillable = ['key', 'value', 'group'];

    protected static ?array $cachedStore = null;

    public static function defaults(): array
    {
        return [
            'store_name' => config('store.name', 'DigitalKit'),
            'store_tagline' => config('store.tagline', 'Produk digital legal, cepat, dan siap dipakai.'),
            'header_subtitle' => 'Produk digital siap checkout WA',
            'whatsapp_number' => config('store.whatsapp', '6281234567890'),
            'support_email' => '',
            'business_address' => '',
            'logo_path' => '',
            'guide_title' => 'Cara membeli produk',
            'guide_subtitle' => 'Ikuti langkah sederhana berikut agar pesanan kamu bisa dikonfirmasi dengan cepat.',
            'qna_title' => 'QnA',
            'qna_subtitle' => 'Temukan jawaban dari pertanyaan yang paling sering ditanyakan sebelum checkout.',
        ];
    }

    public static function store(): array
    {
        if (static::$cachedStore !== null) {
            return static::$cachedStore;
        }

        $settings = static::defaults();

        try {
            if (Schema::hasTable('settings')) {
                $settings = array_merge($settings, static::query()->pluck('value', 'key')->all());
            }
        } catch (Throwable) {
            // Fallback ke default saat migration belum dijalankan.
        }

        return static::$cachedStore = $settings;
    }

    public static function getValue(string $key, ?string $fallback = null): string
    {
        $settings = static::store();

        return (string) ($settings[$key] ?? $fallback ?? '');
    }

    public static function putValue(string $key, ?string $value, string $group = 'store'): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        static::$cachedStore = null;
    }

    public static function logoUrl(): ?string
    {
        $path = trim(static::getValue('logo_path'));

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, ['storage/'])) {
            return asset($path);
        }

        return Storage::url($path);
    }

    public static function whatsappNumber(): string
    {
        return preg_replace('/\D+/', '', static::getValue('whatsapp_number', config('store.whatsapp', '')));
    }
}

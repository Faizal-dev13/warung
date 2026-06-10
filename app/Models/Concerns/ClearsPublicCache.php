<?php

namespace App\Models\Concerns;

use App\Services\PublicCacheService;
use Throwable;

trait ClearsPublicCache
{
    protected static function bootClearsPublicCache(): void
    {
        $clear = function (): void {
            try {
                app(PublicCacheService::class)->flushAll();
            } catch (Throwable) {
                // Jangan sampai aktivitas admin gagal hanya karena cache belum siap saat deploy/migrate.
            }
        };

        static::saved($clear);
        static::deleted($clear);
    }
}

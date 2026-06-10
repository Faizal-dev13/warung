<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PublicCacheService
{
    public const PREFIX = 'public_store:';

    public function remember(string $key, mixed $ttl, callable $callback): mixed
    {
        return Cache::remember($this->key($key), $ttl, $callback);
    }

    public function forget(string $key): void
    {
        Cache::forget($this->key($key));
    }

    public function flushHome(): void
    {
        foreach ([
            'settings',
            'home:banners',
            'home:categories',
            'home:products',
            'home:vouchers',
            'home:qnas',
            'products:categories',
            'qna:list',
            'testimonials:summary',
        ] as $key) {
            $this->forget($key);
        }
    }

    public function flushProduct(?string $slug = null): void
    {
        foreach ([
            'home:products',
            'products:categories',
        ] as $key) {
            $this->forget($key);
        }

        if ($slug) {
            $this->forget('products:detail:'.$slug);
            $this->forget('products:related:'.$slug);
        }
    }

    public function flushAll(): void
    {
        $this->flushHome();
    }

    private function key(string $key): string
    {
        return self::PREFIX.$key;
    }
}

<?php

namespace App\Support;

final class Money
{
    public static function rupiah(int|float|null $amount): string
    {
        return 'Rp'.number_format((float) ($amount ?? 0), 0, ',', '.');
    }
}

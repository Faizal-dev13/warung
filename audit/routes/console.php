<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('store:info', function () {
    $this->info(config('app.name').' siap digunakan.');
});

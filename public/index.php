<?php

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../../warung/vendor/autoload.php';

$app = require_once __DIR__.'/../../warung/bootstrap/app.php';

$app->handleRequest(Illuminate\Http\Request::capture());

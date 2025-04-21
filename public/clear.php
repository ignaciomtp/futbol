<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
Artisan::call('config:cache');
Artisan::call('cache:clear');
echo "Caché limpiada";
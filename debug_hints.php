<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

echo "View Hints:\n";
print_r(view()->getFinder()->getHints());

echo "\nLang Hints:\n";
print_r(app('translator')->getLoader()->namespaces());

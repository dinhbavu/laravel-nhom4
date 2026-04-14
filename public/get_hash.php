<?php
use Illuminate\Support\Facades\Hash;
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());
file_put_contents('hash.txt', Hash::make('admin123'));

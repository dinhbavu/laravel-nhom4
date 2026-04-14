<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$pass = Hash::make('admin123');
DB::table('nguoi_dung')->update(['mat_khau' => $pass]);
echo "ALL_PASSWORDS_RESET_TO_admin123\n";

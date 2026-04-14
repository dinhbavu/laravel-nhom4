<?php

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$admin = NguoiDung::where('email', 'admin')->first();

if ($admin) {
    echo "Updating password for 'admin'...\n";
    $admin->mat_khau = Hash::make('admin123'); // Set to admin123
    $admin->save();
    echo "Updated successfully. New hash: " . $admin->mat_khau . "\n";
} else {
    echo "Admin user not found.\n";
}

$staff = NguoiDung::where('email', 'nhanvien@vietgo.com')->first();
if ($staff) {
    echo "Updating password for 'nhanvien@vietgo.com'...\n";
    $staff->mat_khau = Hash::make('admin123');
    $staff->save();
    echo "Updated successfully.\n";
}

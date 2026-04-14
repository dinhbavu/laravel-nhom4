<?php

use App\Models\NguoiDung;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$admin = NguoiDung::where('email', 'admin')->first();

if ($admin) {
    echo "Email: " . $admin->email . "<br>";
    echo "Password in DB: " . $admin->mat_khau . "<br>";
    echo "Is Bcrypt: " . (password_get_info($admin->mat_khau)['plugin'] === 'bcrypt' ? 'Yes' : 'No') . "<br>";
    
    if (password_get_info($admin->mat_khau)['plugin'] !== 'bcrypt') {
        echo "Updating password to Bcrypt hash for 'admin123'...<br>";
        $admin->mat_khau = \Illuminate\Support\Facades\Hash::make('admin123');
        $admin->save();
        echo "Updated successfully. New hash: " . $admin->mat_khau;
    }
} else {
    echo "Admin user not found.";
}

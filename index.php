<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ── Tự động phát hiện môi trường và SCRIPT_NAME ──────────────────────────
// Đảm bảo Laravel nhận diện đúng subfolder để định tuyến chính xác
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$phpSelf = $_SERVER['PHP_SELF'] ?? '';

// Nếu đang chạy qua index.php ở root (không phải public/)
if (strpos($scriptName, 'index.php') !== false) {
    // Giữ nguyên hoặc chuẩn hóa SCRIPT_NAME
    $_SERVER['SCRIPT_NAME'] = $scriptName;
} elseif (strpos($phpSelf, 'index.php') !== false) {
    $_SERVER['SCRIPT_NAME'] = $phpSelf;
}

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());

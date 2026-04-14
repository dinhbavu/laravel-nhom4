<?php
/**
 * Tạo các thư mục cần thiết cho upload hình ảnh
 * Chạy file này một lần để khởi tạo thư mục
 * 
 * Truy cập URL: https://vietgo.free.nf/create_uploads_dir.php
 */

$directories = [
    'public/uploads',
    'public/uploads/tours',
    'public/uploads/banners',
    'public/uploads/diem-den',
];

$success = true;
$message = "Kiểm tra và tạo thư mục upload:\n\n";

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0755, true)) {
            $message .= "✓ Tạo thành công: $dir\n";
            chmod($fullPath, 0755);
        } else {
            $message .= "✗ Không thể tạo: $dir\n";
            $success = false;
        }
    } else {
        $message .= "✓ Thư mục đã tồn tại: $dir\n";
        // Đảm bảo quyền truy cập
        chmod($fullPath, 0755);
    }
}

// Tạo .htaccess để cho phép truy cập hình ảnh (nếu cần)
$htaccessPath = __DIR__ . '/public/uploads/.htaccess';
if (!file_exists($htaccessPath)) {
    $htaccessContent = <<<'EOT'
<Files "*">
    Order allow,deny
    Allow from all
</Files>
EOT;
    if (file_put_contents($htaccessPath, $htaccessContent)) {
        $message .= "✓ Tạo .htaccess thành công\n";
    }
}

// Hiển thị kết quả
header('Content-Type: text/plain; charset=utf-8');
echo $message;
echo "\n" . ($success ? "Hoàn tất! Bạn có thể xóa file này." : "Có lỗi xảy ra. Vui lòng kiểm tra quyền thư mục.");
?>

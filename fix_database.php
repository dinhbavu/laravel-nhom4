<?php
/**
 * Script kiểm tra và sửa chuỗi database
 * Đảm bảo bảng tour có cột phan_tram_giam_gia
 * Đảm bảo bảng lich_su_dang_nhap tồn tại
 * 
 * Truy cập URL: https://vietgo.free.nf/fix_database.php
 */

// Load environment variables
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $value = trim($value, '\'"');
        putenv(trim($key) . '=' . $value);
    }
}

try {
    // Kết nối database
    $host = getenv('DB_HOST') ?: 'localhost';
    $db = getenv('DB_DATABASE') ?: 'vietgo';
    $user = getenv('DB_USERNAME') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
    
    $pdo = new PDO(
        'mysql:host=' . $host . ';dbname=' . $db,
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
    );
    
    $message = "Kiểm tra và sửa chuỗi database:\n\n";
    $success = true;
    
    // --- Kiểm tra bảng lich_su_dang_nhap ---
    $result = $pdo->query("SHOW TABLES LIKE 'lich_su_dang_nhap'");
    if ($result->rowCount() === 0) {
        try {
            $pdo->exec("
                CREATE TABLE `lich_su_dang_nhap` (
                    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `khach_hang_id` BIGINT UNSIGNED NOT NULL,
                    `ip_address` VARCHAR(45) NULL,
                    `user_agent` TEXT NULL,
                    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    FOREIGN KEY (`khach_hang_id`) REFERENCES `nguoi_dung`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
            $message .= "✓ Tạo bảng lich_su_dang_nhap thành công\n";
        } catch (\Exception $e) {
            $message .= "✗ Lỗi khi tạo bảng lich_su_dang_nhap: " . $e->getMessage() . "\n";
            $success = false;
        }
    } else {
        $message .= "✓ Bảng lich_su_dang_nhap đã tồn tại\n";
    }
    
    // --- Kiểm tra cột phan_tram_giam_gia trong bảng tour ---
    $result = $pdo->query("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'tour' 
        AND COLUMN_NAME = 'phan_tram_giam_gia'
        AND TABLE_SCHEMA = '" . $db . "'
    ");
    
    if ($result->rowCount() === 0) {
        try {
            $pdo->exec("ALTER TABLE `tour` ADD COLUMN `phan_tram_giam_gia` INT DEFAULT 0");
            $message .= "✓ Thêm cột phan_tram_giam_gia vào tour thành công\n";
        } catch (\Exception $e) {
            $message .= "✗ Lỗi khi thêm cột phan_tram_giam_gia: " . $e->getMessage() . "\n";
            $success = false;
        }
    } else {
        $message .= "✓ Cột phan_tram_giam_gia đã tồn tại\n";
    }
    
    header('Content-Type: text/plain; charset=utf-8');
    echo $message;
    echo "\n" . ($success ? "✅ Hoàn tất! Database đã sẵn sàng." : "⚠️ Có lỗi xảy ra. Kiểm tra lại.");
    
} catch (\PDOException $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "⚠️ Lỗi kết nối database: " . $e->getMessage();
}
?>

?>

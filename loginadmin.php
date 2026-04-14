<?php
/**
 * Chuyển hướng người dùng vào trang đăng nhập Admin của Laravel.
 * File này được tạo ra để đáp ứng yêu cầu đường dẫn localhost/VietGo/loginadmin.php
 */

// Lấy scheme (http/https) và host
$host = $_SERVER['HTTP_HOST'];
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

// Lấy đường dẫn cơ sở của folder hiện tại (ví dụ: /VietGo hoặc rỗng)
$baseUrl = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// Tạo đường dẫn redirect tới route loginadmin của Laravel thông qua index.php ở root
// Lưu ý: Chúng ta dùng luôn index.php ở root đã được fix để đảm bảo ổn định
$redirect_url = $protocol . "://" . $host . $baseUrl . "/index.php/loginadmin";

// Chuyển hướng
header("Location: " . $redirect_url);
exit();
?>

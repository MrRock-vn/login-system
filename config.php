<?php
// ============================================
// Cấu hình kết nối cơ sở dữ liệu
// ============================================

define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
define('DB_NAME',     'login_system');
define('DB_PORT',     3306);

function getDBConnection(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

    if ($conn->connect_error) {
        die(json_encode([
            'error' => 'Kết nối cơ sở dữ liệu thất bại: ' . $conn->connect_error
        ]));
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper: kiểm tra đã đăng nhập chưa
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Helper: redirect an toàn
function redirect(string $url): void {
    header("Location: $url");
    exit();
}

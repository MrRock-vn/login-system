<?php
require_once 'config.php';

// Xoá toàn bộ dữ liệu session
$_SESSION = [];

// Xoá cookie session
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 3600,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Chuyển về trang đăng nhập
header('Location: index.php');
exit();

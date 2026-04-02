<?php
require_once 'config.php';

// Chỉ chấp nhận POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validate input cơ bản
if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = 'Vui lòng nhập đầy đủ tên người dùng và mật khẩu.';
    redirect('index.php');
}

// Sanitize để chống SQL Injection (dùng Prepared Statement)
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Không tìm thấy username
    $_SESSION['login_error'] = 'Tên người dùng hoặc mật khẩu không đúng.';
    $stmt->close();
    $conn->close();
    redirect('index.php');
}

$user = $result->fetch_assoc();

// So sánh mật khẩu đã hash bằng password_verify()
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = 'Tên người dùng hoặc mật khẩu không đúng.';
    $stmt->close();
    $conn->close();
    redirect('index.php');
}

// Đăng nhập thành công → lưu session
session_regenerate_id(true); // Chống Session Fixation
$_SESSION['user_id']  = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['login_time'] = time();

$stmt->close();
$conn->close();

redirect('welcome.php');

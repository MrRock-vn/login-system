<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.php');
}

$username         = trim($_POST['username']         ?? '');
$email            = trim($_POST['email']            ?? '');
$password         = trim($_POST['password']         ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

// Lưu lại giá trị cũ để điền lại form
$_SESSION['reg_old'] = ['username' => $username, 'email' => $email];

$errors = [];

// Validate username
if (empty($username)) {
    $errors[] = 'Tên người dùng không được để trống.';
} elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
    $errors[] = 'Tên người dùng chỉ được chứa chữ cái, số, dấu _ và từ 3–30 ký tự.';
}

// Validate email
if (empty($email)) {
    $errors[] = 'Email không được để trống.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Định dạng email không hợp lệ.';
}

// Validate password
if (empty($password)) {
    $errors[] = 'Mật khẩu không được để trống.';
} elseif (strlen($password) < 8) {
    $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự.';
}

// Xác nhận mật khẩu
if ($password !== $confirm_password) {
    $errors[] = 'Mật khẩu xác nhận không khớp.';
}

if (!empty($errors)) {
    $_SESSION['reg_error'] = implode("\n", $errors);
    redirect('register.php');
}

$conn = getDBConnection();

// Kiểm tra username và email đã tồn tại chưa
$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
$check->bind_param('ss', $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['reg_error'] = 'Tên người dùng hoặc email đã được sử dụng.';
    $check->close();
    $conn->close();
    redirect('register.php');
}
$check->close();

// Hash mật khẩu bằng bcrypt (cost 12)
$hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Lưu vào CSDL
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param('sss', $username, $email, $hashed);

if ($stmt->execute()) {
    unset($_SESSION['reg_old']);
    $_SESSION['login_success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
    $stmt->close();
    $conn->close();
    redirect('index.php');
} else {
    $_SESSION['reg_error'] = 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại.';
    $stmt->close();
    $conn->close();
    redirect('register.php');
}

# 🔐 PHP & MySQL Login System — N2

**Thực hành 01/04/2026** — Xây dựng Form Đăng Nhập bằng PHP và MySQL

---

## 📁 Cấu trúc dự án

```
login-system/
├── database.sql          # Script tạo CSDL và bảng users
├── config.php            # Cấu hình kết nối DB + helper functions
├── index.php             # Trang đăng nhập (form)
├── login.php             # Xử lý đăng nhập (POST handler)
├── register.php          # Trang đăng ký tài khoản mới
├── register_handler.php  # Xử lý đăng ký (POST handler)
├── welcome.php           # Trang chào mừng sau khi đăng nhập
├── logout.php            # Xử lý đăng xuất
└── README.md
```

---

## ⚙️ Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7 / MariaDB >= 10.3
- Web server: Apache (XAMPP/WAMP) hoặc Nginx

---

## 🚀 Hướng dẫn cài đặt

### 1. Clone repo

```bash
git clone https://github.com/<username>/login-system.git
cd login-system
```

### 2. Tạo cơ sở dữ liệu

Mở phpMyAdmin hoặc MySQL CLI và chạy:

```sql
source database.sql;
-- hoặc
mysql -u root -p < database.sql
```

### 3. Cấu hình kết nối DB

Mở file `config.php` và chỉnh sửa:

```php
define('DB_HOST',     'localhost');
define('DB_USER',     'root');       // user MySQL của bạn
define('DB_PASSWORD', '');           // password MySQL
define('DB_NAME',     'login_system');
```

### 4. Chạy dự án

Đặt thư mục vào `htdocs/` (XAMPP) hoặc `www/` (WAMP), rồi truy cập:

```
http://localhost/login-system/
```

---

## 🔑 Tài khoản mẫu

| Username | Password   |
|----------|-----------|
| admin    | Admin@123  |

---

## ✅ Tính năng đã thực hiện

| # | Yêu cầu | Trạng thái |
|---|---------|-----------|
| 1 | Tạo CSDL MySQL với bảng `users` (id, username, password) | ✅ |
| 2 | Kết nối PHP → MySQL bằng `mysqli` | ✅ |
| 3 | Trang HTML đăng nhập với tiêu đề "Đăng Nhập" | ✅ |
| 4 | Form có trường username + password | ✅ |
| 5 | Gửi dữ liệu bằng phương thức POST | ✅ |
| 6 | Kiểm tra username + password trong CSDL | ✅ |
| 7 | Thông báo "Đăng nhập thành công / thất bại" | ✅ |
| 8 | Hash mật khẩu bằng `password_hash()` / `password_verify()` | ✅ |
| 9 | Giao diện CSS thân thiện | ✅ |
| 10 | Trang đăng ký người dùng mới | ✅ |

---

## 🔒 Bảo mật

- **Password Hashing**: `password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12])`
- **SQL Injection**: Sử dụng **Prepared Statements** với `bind_param()`
- **XSS**: Dùng `htmlspecialchars()` khi hiển thị dữ liệu người dùng
- **Session Fixation**: `session_regenerate_id(true)` sau khi đăng nhập
- **Input Validation**: Kiểm tra username, email, độ dài mật khẩu

---

## 🗄️ Schema CSDL

```sql
CREATE TABLE users (
  id         INT(11)      NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  email      VARCHAR(100) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at DATETIME     DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);
```

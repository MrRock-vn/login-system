-- ============================================
-- Tạo cơ sở dữ liệu và bảng users
-- Thực hành PHP & MySQL - N2
-- ============================================

CREATE DATABASE IF NOT EXISTS login_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE login_system;

CREATE TABLE IF NOT EXISTS users (
  id       INT(11)      NOT NULL AUTO_INCREMENT,
  username VARCHAR(50)  NOT NULL UNIQUE,
  email    VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME   DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tài khoản mẫu (password: Admin@123)
INSERT INTO users (username, email, password) VALUES (
  'admin',
  'admin@example.com',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

-- Tạo database nếu chưa có
CREATE DATABASE IF NOT EXISTS rongvang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rongvang;

-- Bảng users (tài khoản quản trị)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng posts (bài viết)
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng menus (thực đơn tuần)
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    week_label VARCHAR(100) NOT NULL,
    monday TEXT,
    tuesday TEXT,
    wednesday TEXT,
    thursday TEXT,
    friday TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng students (hồ sơ học sinh)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    nick_name VARCHAR(50),
    age INT,
    parent_name VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng registrations (đơn đăng ký từ phụ huynh)
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    nick_name VARCHAR(50),
    age INT,
    parent_name VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    content TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tài khoản admin mẫu (mật khẩu: 123456, hash SHA-256 là demo, bạn nên thay bằng hash thật khi triển khai)
INSERT INTO users (username, password) VALUES ('admin', 'admin');

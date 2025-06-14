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
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng menus (thực đơn tuần)
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    monday_breakfast TEXT,
    monday_lunch TEXT,
    monday_snack TEXT,
    tuesday_breakfast TEXT,
    tuesday_lunch TEXT,
    tuesday_snack TEXT,
    wednesday_breakfast TEXT,
    wednesday_lunch TEXT,
    wednesday_snack TEXT,
    thursday_breakfast TEXT,
    thursday_lunch TEXT,
    thursday_snack TEXT,
    friday_breakfast TEXT,
    friday_lunch TEXT,
    friday_snack TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_week (start_date, end_date)
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
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_name` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `nick_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `content` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tài khoản admin mẫu (mật khẩu: 123456, hash SHA-256 là demo, bạn nên thay bằng hash thật khi triển khai)
INSERT INTO users (username, password) VALUES ('admin', 'admin');

-- Thêm dữ liệu mẫu cho bảng posts
INSERT INTO posts (title, content, status, created_at) VALUES 
('Khai giảng năm học mới 2024-2025', 'Ngày 5/9, trường Golden Dragon Kindergarten tưng bừng tổ chức lễ khai giảng với nhiều hoạt động hấp dẫn cho các bé.', 'published', NOW()),
('Ngày hội thể thao cho bé', 'Các bé được tham gia nhiều trò chơi vận động, rèn luyện sức khỏe và tinh thần đồng đội trong ngày hội thể thao vừa qua.', 'published', NOW()),
('Workshop kỹ năng sống: Bé tự lập', 'Chương trình giúp các bé học cách tự phục vụ bản thân, phát triển kỹ năng sống ngay từ nhỏ.', 'published', NOW()),
('Bé vui học toán', 'Hoạt động toán học sáng tạo giúp bé phát triển tư duy logic và khả năng giải quyết vấn đề.', 'published', NOW()),
('Bé sáng tạo mỹ thuật', 'Các bé được tự do sáng tạo với màu sắc, chất liệu, phát triển trí tưởng tượng phong phú.', 'published', NOW()),
('Khám phá khoa học', 'Bé được tham gia các thí nghiệm vui, khám phá thế giới xung quanh một cách sinh động.', 'published', NOW()),
('Bé yêu thiên nhiên', 'Hoạt động ngoài trời giúp bé gần gũi thiên nhiên, phát triển thể chất và tinh thần.', 'published', NOW()),
('Bé vui cùng âm nhạc', 'Các tiết học âm nhạc giúp bé phát triển cảm xúc, khả năng cảm thụ nghệ thuật.', 'published', NOW());

-- Thêm dữ liệu mẫu cho thực đơn
INSERT INTO menus (start_date, end_date,
    monday_breakfast, monday_lunch, monday_snack,
    tuesday_breakfast, tuesday_lunch, tuesday_snack,
    wednesday_breakfast, wednesday_lunch, wednesday_snack,
    thursday_breakfast, thursday_lunch, thursday_snack,
    friday_breakfast, friday_lunch, friday_snack) 
VALUES 
(DATE_FORMAT(CURDATE(), '%Y-%m-%d'), DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-%d'), INTERVAL 4 DAY),
    'Cháo thịt bằm', 'Cơm gà xé', 'Sữa chua',
    'Bún bò', 'Cơm cá sốt cà', 'Bánh flan',
    'Bánh mì trứng', 'Cơm thịt kho', 'Trái cây',
    'Phở gà', 'Cơm tôm rim', 'Sữa tươi',
    'Xôi đậu xanh', 'Cơm bò xào', 'Bánh quy');

-- ============================================================
-- AdvertiseMaster — Database Schema (referensi)
-- Anda TIDAK harus mengimpor file ini secara manual.
-- Cukup jalankan install.php di browser, semua tabel + data dibuat otomatis.
-- File ini disediakan untuk dokumentasi / setup manual via phpMyAdmin.
-- ============================================================

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','staff','admin') NOT NULL DEFAULT 'user',
  avatar VARCHAR(255) DEFAULT NULL,
  stars INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ad_number INT NOT NULL,
  title VARCHAR(190) NOT NULL,
  video_url VARCHAR(500) NOT NULL,
  duration INT NOT NULL DEFAULT 15,
  star_reward INT NOT NULL DEFAULT 30,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS watch_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  ad_id INT NOT NULL,
  type ENUM('watch','love') NOT NULL DEFAULT 'watch',
  stars_earned INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(user_id), INDEX(ad_id), INDEX(type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS otps (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  code VARCHAR(6) NOT NULL,
  purpose ENUM('register','reset') NOT NULL,
  payload TEXT DEFAULT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(email), INDEX(purpose)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
  k VARCHAR(100) PRIMARY KEY,
  v TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings (k, v) VALUES ('default_star_reward', '30')
  ON DUPLICATE KEY UPDATE v = v;

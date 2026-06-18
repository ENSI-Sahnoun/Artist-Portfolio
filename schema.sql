-- Run this on your production MySQL database
-- Replace 'strong_password' with a real password

CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'portfolio_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT INSERT, SELECT ON portfolio.* TO 'portfolio_user'@'localhost';
FLUSH PRIVILEGES;

USE portfolio;

CREATE TABLE IF NOT EXISTS clients (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255)  NOT NULL,
    email      VARCHAR(255)  NOT NULL,
    phone      VARCHAR(50)   NOT NULL,
    location   VARCHAR(255),
    address    TEXT          NOT NULL,
    cart       TEXT,
    notes      TEXT,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS testimonials (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(255) NOT NULL,
    email        VARCHAR(255) NOT NULL,
    location     VARCHAR(255),
    artwork_type VARCHAR(100),
    rating       TINYINT      NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review       TEXT         NOT NULL,
    visitation   INT          DEFAULT 0,
    medium       VARCHAR(100),
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

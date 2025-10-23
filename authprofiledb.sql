
<?php
class Database{
CREATE DATABASE IF NOT EXISTS Agriconnectnew;
USE Agriconnectnew;

-- ===========================
-- USER TABLE
-- ===========================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Farmer','Buyer','Admin') NOT NULL,
    contact_no VARCHAR(20),
    status ENUM('Pending','Verified','Rejected','Active','Inactive') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users
INSERT INTO users (name, email, password, role, contact_no, status) VALUES
('Amal Farmer', 'amal@farm.com', '1234', 'Farmer', '0771234567', 'Active'),
('Kamal Buyer', 'kamal@buyer.com', '1234', 'Buyer', '0719876543', 'Active'),
('Admin Nimal', 'admin@agriconnect.com', 'admin123', 'Admin', '0112233445', 'Active');

-- ===========================
-- VERIFICATION LOG
-- ===========================
CREATE TABLE verification_log (
    verification_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    target_type ENUM('User', 'Crop', 'Order') NOT NULL,
    target_id INT NOT NULL,
    action ENUM('Verified', 'Rejected', 'Suspended') NOT NULL,
    remarks TEXT,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin(admin_id)
);

-- Verification Log
INSERT INTO verification_log (admin_id, target_type, target_id, action, remarks)
VALUES
(3, 'User', 1, 'Verified', 'Farmer profile verified successfully.');

ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
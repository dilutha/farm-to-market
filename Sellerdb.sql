CREATE DATABASE IF NOT EXISTS Agriconnect;
USE Agriconnect;

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

CREATE TABLE farmers_profile (
    farmer_id INT PRIMARY KEY,
    farmer_code VARCHAR(6),
    location VARCHAR(255),
    farm_size DECIMAL(10,2),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (farmer_id) REFERENCES users(user_id)
);


CREATE TABLE crop (
    crop_id INT AUTO_INCREMENT PRIMARY KEY,
    farmer_id INT NOT NULL,
    crop_name VARCHAR(100) NOT NULL,
    description TEXT,
    quantity_available DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default_crop.jpg',
    status ENUM('Pending', 'Approved', 'Rejected', 'Inactive') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_id) REFERENCES farmers_profile(farmer_id)
);

-- Users
INSERT INTO users (name, email, password, role, contact_no, status) VALUES
('Amal Farmer', 'amal@farm.com', '1234', 'Farmer', '0771234567', 'Active'),
('Kamal Buyer', 'kamal@buyer.com', '1234', 'Buyer', '0719876543', 'Active'),
('Admin Nimal', 'admin@agriconnect.com', 'admin123', 'Admin', '0112233445', 'Active');

INSERT INTO farmers_profile (farmer_id, farmer_code, location, farm_size, verification_status)
VALUES (1, 'F0001', 'Kurunegala', 10.5, 'Verified');

INSERT INTO crop (farmer_id, crop_name, description, quantity_available, price, status)
VALUES
(1, 'Tomatoes', 'Fresh organic tomatoes', 100.00, 120.00, 'Approved'),
(1, 'Carrots', 'Home-grown carrots', 50.00, 80.00, 'Approved');

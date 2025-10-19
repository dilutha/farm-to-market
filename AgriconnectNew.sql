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





-- ===========================
-- FARMER PROFILE
-- ===========================
CREATE TABLE farmers_profile (
    farmer_id INT PRIMARY KEY,
    farmer_code VARCHAR(6),
    location VARCHAR(255),
    farm_size DECIMAL(10,2),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (farmer_id) REFERENCES users(user_id)
);

-- ===========================
-- BUYER PROFILE
-- ===========================
CREATE TABLE buyer_profile (
    buyer_id INT PRIMARY KEY,
    buyer_code VARCHAR(6),
    company_name VARCHAR(255),
    address VARCHAR(255),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (buyer_id) REFERENCES users(user_id)
);

-- ===========================
-- ADMIN TABLE
-- ===========================
CREATE TABLE admin (
    admin_id INT PRIMARY KEY,
    designation VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
);

-- ===========================
-- CROP TABLE
-- ===========================
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

-- ===========================
-- ORDERS TABLE
-- ===========================
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- MARKET PREDICTION
-- ===========================
CREATE TABLE market_prediction (
    prediction_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    predicted_price DECIMAL(10,2),
    predicted_demand DECIMAL(10,2),
    prediction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    model_version VARCHAR(50),
    status ENUM('Active', 'Outdated') DEFAULT 'Active',
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

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

-- ===========================
-- INQUIRY TABLE
-- ===========================
CREATE TABLE inquiries (
    inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    response TEXT,
    status ENUM('Open', 'In Progress', 'Closed') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- ===========================
-- PAYMENT TABLE
-- ===========================
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Credit Card', 'Bank Transfer', 'Cash', 'Mobile Payment') NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    status ENUM('Active', 'Archived') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- ===========================
-- CART TABLE
-- ===========================
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    status ENUM('Active','Removed','CheckedOut') DEFAULT 'Active',
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- DEMO DATA
-- ===========================

-- Users
INSERT INTO users (name, email, password, role, contact_no, status) VALUES
('Amal Farmer', 'amal@farm.com', '1234', 'Farmer', '0771234567', 'Active'),
('Kamal Buyer', 'kamal@buyer.com', '1234', 'Buyer', '0719876543', 'Active'),
('Admin Nimal', 'admin@agriconnect.com', 'admin123', 'Admin', '0112233445', 'Active');

-- Farmer, Buyer, Admin Profiles
INSERT INTO farmers_profile (farmer_id, farmer_code, location, farm_size, verification_status)
VALUES (1, 'F0001', 'Kurunegala', 10.5, 'Verified');

INSERT INTO buyer_profile (buyer_id, buyer_code, company_name, address, verification_status)
VALUES (2, 'S0001', 'FreshMart Pvt Ltd', 'Colombo', 'Verified');

INSERT INTO admin (admin_id, designation)
VALUES (3, 'System Admin');

-- Crops
INSERT INTO crop (farmer_id, crop_name, description, quantity_available, price, status)
VALUES
(1, 'Tomatoes', 'Fresh organic tomatoes', 100.00, 120.00, 'Approved'),
(1, 'Carrots', 'Home-grown carrots', 50.00, 80.00, 'Approved');

-- Orders
INSERT INTO orders (buyer_id, crop_id, quantity, total_price, status)
VALUES
(2, 1, 10, 1200.00, 'Approved'),
(2, 2, 5, 400.00, 'Delivered');

-- Payment
INSERT INTO payment (order_id, amount, payment_method, payment_status)
VALUES
(1, 1200.00, 'Bank Transfer', 'Completed'),
(2, 400.00, 'Mobile Payment', 'Completed');

-- Cart
INSERT INTO cart (buyer_id, crop_id, quantity)
VALUES
(2, 1, 3),
(2, 2, 2);

-- Market Prediction
INSERT INTO market_prediction (crop_id, predicted_price, predicted_demand, model_version)
VALUES
(1, 130.00, 500.00, 'v1.0');

-- Inquiry
INSERT INTO inquiry (user_id, subject, message)
VALUES
(2, 'Delivery Delay', 'Order delivery was late by 2 days.');

-- Verification Log
INSERT INTO verification_log (admin_id, target_type, target_id, action, remarks)
VALUES
(3, 'User', 1, 'Verified', 'Farmer profile verified successfully.');

ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


USE Agriconnectnew;
SHOW TABLES;
select * from orders;

ALTER TABLE buyer_profile
ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- rename the old orders table to keep it as a backup
RENAME TABLE orders TO orders_old;

-- create the new orders (header) table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id)
);

-- create order_items table for multiple items
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- 5. Migrate header rows
INSERT INTO orders (order_id, buyer_id, total_price, status, created_at)
SELECT order_id, buyer_id, total_price, status, created_at
FROM orders_old;

-- 6. Migrate items (one-per-old-row)
INSERT INTO order_items (order_id, crop_id, quantity, unit_price)
SELECT 
  order_id,
  crop_id,
  quantity,
  CASE WHEN quantity = 0 THEN 0 ELSE ROUND(total_price / quantity, 2) END
FROM orders_old;



-- 8. Recreate FK for payment linking to the new orders table
ALTER TABLE payment
  ADD CONSTRAINT fk_payment_order
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

ALTER TABLE payment DROP FOREIGN KEY payment_ibfk_1;

SHOW CREATE TABLE payment;

ALTER TABLE payment
ADD CONSTRAINT fk_payment_order
FOREIGN KEY (order_id) REFERENCES orders(order_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

DROP TABLE orders_old;

SELECT * FROM orders;
SELECT * FROM order_items;
SELECT * FROM payment;


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





-- ===========================
-- FARMER PROFILE
-- ===========================
CREATE TABLE farmers_profile (
    farmer_id INT PRIMARY KEY,
    farmer_code VARCHAR(6),
    location VARCHAR(255),
    farm_size DECIMAL(10,2),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (farmer_id) REFERENCES users(user_id)
);

-- ===========================
-- BUYER PROFILE
-- ===========================
CREATE TABLE buyer_profile (
    buyer_id INT PRIMARY KEY,
    buyer_code VARCHAR(6),
    company_name VARCHAR(255),
    address VARCHAR(255),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (buyer_id) REFERENCES users(user_id)
);

-- ===========================
-- ADMIN TABLE
-- ===========================
CREATE TABLE admin (
    admin_id INT PRIMARY KEY,
    designation VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
);

-- ===========================
-- CROP TABLE
-- ===========================
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

-- ===========================
-- ORDERS TABLE
-- ===========================
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- MARKET PREDICTION
-- ===========================
CREATE TABLE market_prediction (
    prediction_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    predicted_price DECIMAL(10,2),
    predicted_demand DECIMAL(10,2),
    prediction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    model_version VARCHAR(50),
    status ENUM('Active', 'Outdated') DEFAULT 'Active',
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

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

-- ===========================
-- INQUIRY TABLE
-- ===========================
CREATE TABLE inquiries (
    inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    response TEXT,
    status ENUM('Open', 'In Progress', 'Closed') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- ===========================
-- PAYMENT TABLE
-- ===========================
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Credit Card', 'Bank Transfer', 'Cash', 'Mobile Payment') NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    status ENUM('Active', 'Archived') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- ===========================
-- CART TABLE
-- ===========================
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    status ENUM('Active','Removed','CheckedOut') DEFAULT 'Active',
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- DEMO DATA
-- ===========================

-- Users
INSERT INTO users (name, email, password, role, contact_no, status) VALUES
('Amal Farmer', 'amal@farm.com', '1234', 'Farmer', '0771234567', 'Active'),
('Kamal Buyer', 'kamal@buyer.com', '1234', 'Buyer', '0719876543', 'Active'),
('Admin Nimal', 'admin@agriconnect.com', 'admin123', 'Admin', '0112233445', 'Active');

-- Farmer, Buyer, Admin Profiles
INSERT INTO farmers_profile (farmer_id, farmer_code, location, farm_size, verification_status)
VALUES (1, 'F0001', 'Kurunegala', 10.5, 'Verified');

INSERT INTO buyer_profile (buyer_id, buyer_code, company_name, address, verification_status)
VALUES (2, 'S0001', 'FreshMart Pvt Ltd', 'Colombo', 'Verified');

INSERT INTO admin (admin_id, designation)
VALUES (3, 'System Admin');

-- Crops
INSERT INTO crop (farmer_id, crop_name, description, quantity_available, price, status)
VALUES
(1, 'Tomatoes', 'Fresh organic tomatoes', 100.00, 120.00, 'Approved'),
(1, 'Carrots', 'Home-grown carrots', 50.00, 80.00, 'Approved');

-- Orders
INSERT INTO orders (buyer_id, crop_id, quantity, total_price, status)
VALUES
(2, 1, 10, 1200.00, 'Approved'),
(2, 2, 5, 400.00, 'Delivered');

-- Payment
INSERT INTO payment (order_id, amount, payment_method, payment_status)
VALUES
(1, 1200.00, 'Bank Transfer', 'Completed'),
(2, 400.00, 'Mobile Payment', 'Completed');

-- Cart
INSERT INTO cart (buyer_id, crop_id, quantity)
VALUES
(2, 1, 3),
(2, 2, 2);

-- Market Prediction
INSERT INTO market_prediction (crop_id, predicted_price, predicted_demand, model_version)
VALUES
(1, 130.00, 500.00, 'v1.0');

-- Inquiry
INSERT INTO inquiry (user_id, subject, message)
VALUES
(2, 'Delivery Delay', 'Order delivery was late by 2 days.');

-- Verification Log
INSERT INTO verification_log (admin_id, target_type, target_id, action, remarks)
VALUES
(3, 'User', 1, 'Verified', 'Farmer profile verified successfully.');

ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


USE Agriconnectnew;
SHOW TABLES;
select * from orders;

ALTER TABLE buyer_profile
ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- rename the old orders table to keep it as a backup
RENAME TABLE orders TO orders_old;

-- create the new orders (header) table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id)
);

-- create order_items table for multiple items
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- 5. Migrate header rows
INSERT INTO orders (order_id, buyer_id, total_price, status, created_at)
SELECT order_id, buyer_id, total_price, status, created_at
FROM orders_old;

-- 6. Migrate items (one-per-old-row)
INSERT INTO order_items (order_id, crop_id, quantity, unit_price)
SELECT 
  order_id,
  crop_id,
  quantity,
  CASE WHEN quantity = 0 THEN 0 ELSE ROUND(total_price / quantity, 2) END
FROM orders_old;



-- 8. Recreate FK for payment linking to the new orders table
ALTER TABLE payment
  ADD CONSTRAINT fk_payment_order
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

ALTER TABLE payment DROP FOREIGN KEY payment_ibfk_1;

SHOW CREATE TABLE payment;

ALTER TABLE payment
ADD CONSTRAINT fk_payment_order
FOREIGN KEY (order_id) REFERENCES orders(order_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

DROP TABLE orders_old;

SELECT * FROM orders;
SELECT * FROM order_items;
SELECT * FROM payment;



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





-- ===========================
-- FARMER PROFILE
-- ===========================
CREATE TABLE farmers_profile (
    farmer_id INT PRIMARY KEY,
    farmer_code VARCHAR(6),
    location VARCHAR(255),
    farm_size DECIMAL(10,2),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (farmer_id) REFERENCES users(user_id)
);

-- ===========================
-- BUYER PROFILE
-- ===========================
CREATE TABLE buyer_profile (
    buyer_id INT PRIMARY KEY,
    buyer_code VARCHAR(6),
    company_name VARCHAR(255),
    address VARCHAR(255),
    verification_status ENUM('Pending','Verified','Rejected') DEFAULT 'Pending',
    status ENUM('Active', 'Inactive', 'Suspended') DEFAULT 'Active',
    FOREIGN KEY (buyer_id) REFERENCES users(user_id)
);

-- ===========================
-- ADMIN TABLE
-- ===========================
CREATE TABLE admin (
    admin_id INT PRIMARY KEY,
    designation VARCHAR(100),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
);

-- ===========================
-- CROP TABLE
-- ===========================
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

-- ===========================
-- ORDERS TABLE
-- ===========================
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- MARKET PREDICTION
-- ===========================
CREATE TABLE market_prediction (
    prediction_id INT AUTO_INCREMENT PRIMARY KEY,
    crop_id INT NOT NULL,
    predicted_price DECIMAL(10,2),
    predicted_demand DECIMAL(10,2),
    prediction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    model_version VARCHAR(50),
    status ENUM('Active', 'Outdated') DEFAULT 'Active',
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

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

-- ===========================
-- INQUIRY TABLE
-- ===========================
CREATE TABLE inquiries (
    inquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    response TEXT,
    status ENUM('Open', 'In Progress', 'Closed') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);


-- ===========================
-- PAYMENT TABLE
-- ===========================
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('Credit Card', 'Bank Transfer', 'Cash', 'Mobile Payment') NOT NULL,
    payment_status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    status ENUM('Active', 'Archived') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

-- ===========================
-- CART TABLE
-- ===========================
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    status ENUM('Active','Removed','CheckedOut') DEFAULT 'Active',
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id),
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- ===========================
-- DEMO DATA
-- ===========================

-- Users
INSERT INTO users (name, email, password, role, contact_no, status) VALUES
('Amal Farmer', 'amal@farm.com', '1234', 'Farmer', '0771234567', 'Active'),
('Kamal Buyer', 'kamal@buyer.com', '1234', 'Buyer', '0719876543', 'Active'),
('Admin Nimal', 'admin@agriconnect.com', 'admin123', 'Admin', '0112233445', 'Active');

-- Farmer, Buyer, Admin Profiles
INSERT INTO farmers_profile (farmer_id, farmer_code, location, farm_size, verification_status)
VALUES (1, 'F0001', 'Kurunegala', 10.5, 'Verified');

INSERT INTO buyer_profile (buyer_id, buyer_code, company_name, address, verification_status)
VALUES (2, 'S0001', 'FreshMart Pvt Ltd', 'Colombo', 'Verified');

INSERT INTO admin (admin_id, designation)
VALUES (3, 'System Admin');

-- Crops
INSERT INTO crop (farmer_id, crop_name, description, quantity_available, price, status)
VALUES
(1, 'Tomatoes', 'Fresh organic tomatoes', 100.00, 120.00, 'Approved'),
(1, 'Carrots', 'Home-grown carrots', 50.00, 80.00, 'Approved');

-- Orders
INSERT INTO orders (buyer_id, crop_id, quantity, total_price, status)
VALUES
(2, 1, 10, 1200.00, 'Approved'),
(2, 2, 5, 400.00, 'Delivered');

-- Payment
INSERT INTO payment (order_id, amount, payment_method, payment_status)
VALUES
(1, 1200.00, 'Bank Transfer', 'Completed'),
(2, 400.00, 'Mobile Payment', 'Completed');

-- Cart
INSERT INTO cart (buyer_id, crop_id, quantity)
VALUES
(2, 1, 3),
(2, 2, 2);

-- Market Prediction
INSERT INTO market_prediction (crop_id, predicted_price, predicted_demand, model_version)
VALUES
(1, 130.00, 500.00, 'v1.0');

-- Inquiry
INSERT INTO inquiry (user_id, subject, message)
VALUES
(2, 'Delivery Delay', 'Order delivery was late by 2 days.');

-- Verification Log
INSERT INTO verification_log (admin_id, target_type, target_id, action, remarks)
VALUES
(3, 'User', 1, 'Verified', 'Farmer profile verified successfully.');

ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


USE Agriconnectnew;
SHOW TABLES;
select * from orders;

ALTER TABLE buyer_profile
ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- rename the old orders table to keep it as a backup
RENAME TABLE orders TO orders_old;

-- create the new orders (header) table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('Pending', 'Approved', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES buyer_profile(buyer_id)
);

-- create order_items table for multiple items
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    crop_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (crop_id) REFERENCES crop(crop_id)
);

-- 5. Migrate header rows
INSERT INTO orders (order_id, buyer_id, total_price, status, created_at)
SELECT order_id, buyer_id, total_price, status, created_at
FROM orders_old;

-- 6. Migrate items (one-per-old-row)
INSERT INTO order_items (order_id, crop_id, quantity, unit_price)
SELECT 
  order_id,
  crop_id,
  quantity,
  CASE WHEN quantity = 0 THEN 0 ELSE ROUND(total_price / quantity, 2) END
FROM orders_old;



-- 8. Recreate FK for payment linking to the new orders table
ALTER TABLE payment
  ADD CONSTRAINT fk_payment_order
  FOREIGN KEY (order_id) REFERENCES orders(order_id)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

ALTER TABLE payment DROP FOREIGN KEY payment_ibfk_1;

SHOW CREATE TABLE payment;

ALTER TABLE payment
ADD CONSTRAINT fk_payment_order
FOREIGN KEY (order_id) REFERENCES orders(order_id)
ON DELETE RESTRICT
ON UPDATE CASCADE;

DROP TABLE orders_old;

SELECT * FROM orders;
SELECT * FROM order_items;
SELECT * FROM payment;

SHOW TABLES;
SHOW CREATE TABLE payment;

ALTER TABLE crop

ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE crop ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL;

select*from crop;


SELECT * FROM farmers_profile WHERE farmer_id = 1;

select * from users;


ALTER TABLE farmers_profile
ADD COLUMN created_at TIMESTAMP NULL,
ADD COLUMN updated_at TIMESTAMP NULL;




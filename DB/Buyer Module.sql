CREATE DATABASE FarmtoMarket;
USE FarmtoMarket;

CREATE TABLE Orders(
order_id int auto_increment PRIMARY KEY,
order_qty int,
order_date datetime,
order_status Enum('pending', 'completed', 'cancelled')
NOT NULL default 'pending' );

CREATE TABLE Price_Alert(
alert_id int auto_increment PRIMARY KEY,
target_price DECIMAL(10,2),
target_status Enum('pending', 'completed', 'cancelled')
NOT NULL default 'pending');

DROP DATABASE FarmtoMarket;

CREATE TABLE demand_forecast(
forecast_id INT auto_increment PRIMARY KEY,
/*FOREIGN KEY (crop_id) references Crop_Listing(Listing_id),*/
forecast_date datetime,
predicted_qty decimal(10,2),
unit varchar(255),
predicted_price decimal(10,2),
confidence_level decimal(5,2),
created_at timestamp default current_timestamp,
updated_at timestamp default current_timestamp 
on update current_timestamp );

INSERT INTO Orders (order_qty, order_date, order_status)
VALUES
(50.00, '2025-09-19', 'pending'),
(120.50, '2025-09-18', 'completed'),
(75.00, '2025-09-17', 'pending'),
(200.00, '2025-09-16', 'completed'),
(30.00, '2025-09-15', 'cancelled'),
(150.25, '2025-09-14', 'pending');

INSERT INTO Price_Alert (target_price, target_status)
VALUES
(250.00, 'pending'),
(300.50, 'completed'),
(150.75, 'pending'),
(400.00, 'completed'),
(200.00, 'cancelled'),
(275.25, 'pending');

INSERT INTO demand_forecast (forecast_date, predicted_qty, unit, predicted_price, confidence_level)
VALUES
('2025-09-20 00:00:00', 500.00, 'kg', 250.00, 95.50),
('2025-09-21 00:00:00', 1200.50, 'kg', 150.00, 90.00),
('2025-09-22 00:00:00', 800.00, 'kg', 300.00, 92.00),
('2025-09-23 00:00:00', 450.00, 'kg', 260.00, 94.00),
('2025-09-24 00:00:00', 1300.00, 'kg', 155.00, 89.50);














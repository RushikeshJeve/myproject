CREATE DATABASE IF NOT EXISTS onlineresrvation;
USE onlineresrvation;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    travel_date DATE NOT NULL,
    travel_time TIME NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seat_number VARCHAR(3) NOT NULL,
    travel_date DATE NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available',
    ticket_id INT,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    UNIQUE KEY unique_seat_date (seat_number, travel_date)
);

-- Insert initial seat numbers
INSERT INTO seats (seat_number, travel_date, status)
SELECT seat_num, CURDATE(), 'available'
FROM (
    SELECT CONCAT('A', numbers.n) as seat_num
    FROM (
        SELECT 1 as n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
        UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
    ) numbers
    UNION ALL
    SELECT CONCAT('B', numbers.n) as seat_num
    FROM (
        SELECT 1 as n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
        UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
    ) numbers
) all_seats;

CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO seats (seat_number, travel_date, status) VALUES
('A1', '2025-01-03', 'available'),
('A2', '2025-01-03', 'available'),
('A3', '2025-01-03', 'available'),
('A4', '2025-01-03', 'available'),
('A5', '2025-01-03', 'available'),
('A6', '2025-01-03', 'available'),
('A7', '2025-01-03', 'available'),
('A8', '2025-01-03', 'available'),
('A9', '2025-01-03', 'available'),
('A10', '2025-01-03', 'available'),
('B1', '2025-01-03', 'available'),
('B2', '2025-01-03', 'available'),
('B3', '2025-01-03', 'available'),
('B4', '2025-01-03', 'available'),
('B5', '2025-01-03', 'available'),
('B6', '2025-01-03', 'available'),
('B7', '2025-01-03', 'available'),
('B8', '2025-01-03', 'available'),
('B9', '2025-01-03', 'available'),
('B10', '2025-01-03', 'available');

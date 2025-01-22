 CREATE DATABASE IF NOT EXISTS onlineresrvation;
USE onlineresrvation;
 
 -- Create the bus_pune_to_mumbai table
CREATE TABLE bus_pune_to_mumbai (
    seat_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    seat_no VARCHAR(5) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 0, -- 0 for not booked, 1 for booked
    travel_date DATE NOT NULL
);

-- Insert 20 rows into the bus_pune_to_mumbai table
INSERT INTO bus_pune_to_mumbai (user_id, seat_no, status, travel_date) VALUES
(NULL, 'A1', 0, '2025-01-18'),
(NULL, 'A2', 0, '2025-01-18'),
(NULL, 'A3', 0, '2025-01-18'),
(NULL, 'A4', 0, '2025-01-18'),
(NULL, 'A5', 0, '2025-01-18'),
(NULL, 'A6', 0, '2025-01-18'),
(NULL, 'A7', 0, '2025-01-18'),
(NULL, 'A8', 0, '2025-01-18'),
(NULL, 'A9', 0, '2025-01-18'),
(NULL, 'A10', 0, '2025-01-18'),
(NULL, 'A11', 0, '2025-01-18'),
(NULL, 'A12', 0, '2025-01-18'),
(NULL, 'A13', 0, '2025-01-18'),
(NULL, 'A14', 0, '2025-01-18'),
(NULL, 'A15', 0, '2025-01-18'),
(NULL, 'A16', 0, '2025-01-18'),
(NULL, 'A17', 0, '2025-01-18'),
(NULL, 'A18', 0, '2025-01-18'),
(NULL, 'A19', 0, '2025-01-18'),
(NULL, 'A20', 0, '2025-01-18');




-- Create the bus_mumbai_to_pune table
CREATE TABLE bus_mumbai_to_pune (
    seat_id INT PRIMARY KEY, -- Fixed ID for 20 rows
    user_id INT DEFAULT NULL, -- ID of the user who booked the seat
    seat_no VARCHAR(50) NOT NULL UNIQUE, -- Unique seat name
    status TINYINT(1) NOT NULL DEFAULT 0, -- 0 for not booked, 1 for booked
    travel_date DATE NOT NULL -- Date of the journey
);

-- Insert exactly 20 rows into the table
INSERT INTO bus_mumbai_to_pune (seat_id, user_id, seat_no, status, travel_date) VALUES
(1, NULL, 'A1', 0, '2025-01-18'),
(2, NULL, 'A2', 0, '2025-01-18'),
(3, NULL, 'A3', 0, '2025-01-18'),
(4, NULL, 'A4', 1, '2025-01-18'), 
(5, NULL, 'A5', 0, '2025-01-18'),
(6, NULL, 'A6', 0, '2025-01-18'),
(7, NULL, 'A7', 0, '2025-01-18'),
(8, NULL, 'A8', 0, '2025-01-18'),
(9, NULL, 'A9', 0, '2025-01-18'),
(10, NULL, 'A10', 0, '2025-01-18'),
(11, NULL, 'A11', 1, '2025-01-18'), 
(12, NULL, 'A12', 0, '2025-01-18'),
(13, NULL, 'A13', 0, '2025-01-18'),
(14, NULL, 'A14', 0, '2025-01-18'),
(15, NULL, 'A15', 0, '2025-01-18'),
(16, NULL, 'A16', 0, '2025-01-18'),
(17, NULL, 'A17', 1, '2025-01-18'), 
(18, NULL, 'A18', 0, '2025-01-18'),
(19, NULL, 'A19', 0, '2025-01-18'),
(20, NULL, 'A20', 1, '2025-01-18'); 
alter table bus_mumbai_to_pune modify seat_no varchar(255);

UPDATE bus_mumbai_to_pune SET status = 0, user_id = NULL;





CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS tickets (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique identifier for each ticket, auto-incremented
    user_id INT NOT NULL,                            -- ID of the user who booked the ticket (foreign key reference to the 'users' table)
    total_amount DECIMAL(10, 2) NOT NULL,            -- Total amount for the ticket
    booking_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Time when the booking was made
    seats VARCHAR(255) NOT NULL,                     -- Seats booked (e.g., 'A1, A2, A3')
    user_name VARCHAR(100) NOT NULL,                 -- Name of the user who booked the ticket
    route_name VARCHAR(100) NOT NULL,                -- Name of the route (e.g., 'Mumbai to Pune', 'Pune to Mumbai')
    travel_date DATE NOT NULL,                       -- Date of travel
    travel_time TIME NOT NULL,                       -- Time of travel
    FOREIGN KEY (user_id) REFERENCES users(id)      -- Foreign key reference to the 'users' table
);




select * from tickets;
DELETE FROM tickets;





CREATE TABLE IF NOT EXISTS user_ticket_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique identifier for each history entry
    user_id INT NOT NULL,                            -- ID of the user who booked the ticket
    ticket_id INT NOT NULL,                          -- Reference to the original ticket ID
    user_name VARCHAR(100) NOT NULL,                 -- Name of the user
    route_name VARCHAR(100) NOT NULL,                -- Name of the route (e.g., 'Mumbai to Pune')
    seats VARCHAR(255) NOT NULL,                     -- Seats booked (e.g., 'A1, A2')
    travel_date DATE NOT NULL,                       -- Date of travel
    travel_time TIME NOT NULL,                       -- Time of travel
    total_amount DECIMAL(10, 2) NOT NULL,            -- Total amount for the ticket
    booking_time TIMESTAMP NOT NULL,                 -- Time when the ticket was originally booked
    cancellation_time TIMESTAMP DEFAULT NULL,        -- Time when the ticket was canceled (if applicable)
    status ENUM('Active', 'Canceled') NOT NULL       -- Status of the ticket
);


DELETE FROM user_ticket_history;


CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);





uwgq piho ghvu fglq





mysql> DELETE FROM tickets WHERE user_id = 7;
Query OK, 5 rows affected (0.02 sec)

mysql> DELETE FROM users WHERE id = 7;
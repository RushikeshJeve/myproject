<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Drop the existing database
$query = "DROP DATABASE IF EXISTS onlinereservation";
if ($conn->query($query) === TRUE) {
    echo "Database dropped successfully.<br>";
} else {
    echo "Error dropping database: " . $conn->error . "<br>";
}

// Create the database
$query = "CREATE DATABASE IF NOT EXISTS onlinereservation";
if ($conn->query($query) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Use the database
$conn->select_db("onlinereservation");

// Create the users table
$query = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($query) === TRUE) {
    echo "Users table created successfully.<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create the tickets table
$query = "
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
)";
if ($conn->query($query) === TRUE) {
    echo "Tickets table created successfully.<br>";
} else {
    echo "Error creating tickets table: " . $conn->error . "<br>";
}

// Create the seats table
$query = "
CREATE TABLE IF NOT EXISTS seats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seat_number VARCHAR(3) NOT NULL,
    travel_date DATE NOT NULL,
    status ENUM('available', 'booked') DEFAULT 'available',
    ticket_id INT,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id),
    UNIQUE KEY unique_seat_date (seat_number, travel_date)
)";
if ($conn->query($query) === TRUE) {
    echo "Seats table created successfully.<br>";
} else {
    echo "Error creating seats table: " . $conn->error . "<br>";
}

// Insert initial seat numbers
$query = "
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
) all_seats";
if ($conn->query($query) === TRUE) {
    echo "Initial seat numbers inserted successfully.<br>";
} else {
    echo "Error inserting initial seat numbers: " . $conn->error . "<br>";
}

// Create the admins table
$query = "
CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($query) === TRUE) {
    echo "Admins table created successfully.<br>";
} else {
    echo "Error creating admins table: " . $conn->error . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="view-reports-container">
        <h1>View Reports</h1>
        <p>Database reset completed. All tables have been recreated and initial data has been inserted.</p>
    </div>
</body>
</html>

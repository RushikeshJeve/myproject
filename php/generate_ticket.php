<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['seats']) || !isset($_POST['amount'])) {
    header("Location: dashboard.php");
    exit();
}

// Insert ticket into database
$userId = $_SESSION['user_id'];
$source = $_SESSION['from_location'];
$destination = $_SESSION['to_location'];
$travelDate = $_SESSION['date'];
$travelTime = $_SESSION['time'];
$amount = $_POST['amount'];

$sql = "INSERT INTO tickets (user_id, source, destination, travel_date, travel_time, amount) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssd", $userId, $source, $destination, $travelDate, $travelTime, $amount);
$stmt->execute();
$ticketId = $conn->insert_id;

// Update seats status
$selectedSeats = explode(',', $_POST['seats']);
foreach ($selectedSeats as $seat) {
    $sql = "UPDATE seats SET status = 'booked', ticket_id = ? WHERE seat_number = ? AND travel_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $ticketId, $seat, $travelDate);
    $stmt->execute();
}

// Generate ticket content
$ticketContent = "=== ONLINE TICKET BOOKING - E-TICKET ===\n\n";
$ticketContent .= "Ticket ID: " . str_pad($ticketId, 8, '0', STR_PAD_LEFT) . "\n";
$ticketContent .= "Booking Date: " . date('Y-m-d H:i:s') . "\n\n";
$ticketContent .= "Passenger Details:\n";
$ticketContent .= "Name: " . $_SESSION['user_name'] . "\n\n";
$ticketContent .= "Journey Details:\n";
$ticketContent .= "From: " . $source . "\n";
$ticketContent .= "To: " . $destination . "\n";
$ticketContent .= "Date: " . $travelDate . "\n";
$ticketContent .= "Time: " . $travelTime . "\n";
$ticketContent .= "Seats: " . $_POST['seats'] . "\n\n";
$ticketContent .= "Amount Paid: Rs. " . $amount . "\n\n";
$ticketContent .= "=== Important Instructions ===\n";
$ticketContent .= "1. Please carry a valid ID proof while traveling\n";
$ticketContent .= "2. Reach the boarding point 30 minutes before departure\n";
$ticketContent .= "3. This is an electronic ticket, please take a printout\n\n";
$ticketContent .= "Thank you for choosing our service!\n";
$ticketContent .= "=================================";

// Store ticket content in session for download
$_SESSION['ticket_content'] = $ticketContent;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Generated - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .ticket-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 2rem;
        }

        .ticket {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .ticket-header {
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
            margin-bottom: 2rem;
        }

        .ticket-content {
            margin-bottom: 2rem;
        }

        .ticket-section {
            margin-bottom: 1.5rem;
        }

        .ticket-section h3 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .ticket-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .ticket-info p {
            margin: 0.5rem 0;
            color: #666;
        }

        .download-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
        }

        .download-button:hover {
            background: #0056b3;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand">
            <a href="dashboard.php">Ticket Booking</a>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="dashboard.php#cities">Cities</a></li>
            <li><a href="dashboard.php#help">Help</a></li>
            <li class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </li>
        </ul>
    </nav>

    <div class="ticket-container">
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            Booking Successful! Your ticket has been generated.
        </div>

        <div class="ticket">
            <div class="ticket-header">
                <h2>E-Ticket</h2>
                <p>Ticket ID: <?php echo str_pad($ticketId, 8, '0', STR_PAD_LEFT); ?></p>
            </div>

            <div class="ticket-content">
                <div class="ticket-section">
                    <h3>Passenger Details</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                </div>

                <div class="ticket-section">
                    <h3>Journey Details</h3>
                    <div class="ticket-info">
                        <p><strong>From:</strong> <?php echo htmlspecialchars($source); ?></p>
                        <p><strong>To:</strong> <?php echo htmlspecialchars($destination); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($travelDate); ?></p>
                        <p><strong>Time:</strong> <?php echo htmlspecialchars($travelTime); ?></p>
                        <p><strong>Seats:</strong> <?php echo htmlspecialchars($_POST['seats']); ?></p>
                        <p><strong>Amount Paid:</strong> Rs. <?php echo htmlspecialchars($amount); ?></p>
                    </div>
                </div>

                <div class="ticket-section">
                    <h3>Important Instructions</h3>
                    <ol>
                        <li>Please carry a valid ID proof while traveling</li>
                        <li>Reach the boarding point 30 minutes before departure</li>
                        <li>This is an electronic ticket, please take a printout</li>
                    </ol>
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="download_ticket.php" class="download-button">
                <i class="fas fa-download"></i>
                Download Ticket
            </a>
        </div>
    </div>
</body>
</html>

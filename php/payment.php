<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['seats']) || !isset($_GET['total'])) {
    header("Location: dashboard.php");
    exit();
}

$selectedSeats = $_GET['seats'];
$totalAmount = $_GET['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .payment-summary {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .qr-container {
            text-align: center;
            margin: 2rem 0;
        }

        .qr-code {
            max-width: 200px;
            margin: 0 auto;
        }

        .upi-id {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin: 1rem 0;
        }

        .payment-instructions {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        .payment-instructions ol {
            margin: 0;
            padding-left: 1.5rem;
        }

        .payment-instructions li {
            margin: 0.5rem 0;
            color: #666;
        }

        .confirm-button {
            background: #28a745;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }

        .confirm-button:hover {
            background: #218838;
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

    <div class="payment-container">
        <h2>Payment Details</h2>
        
        <div class="payment-summary">
            <p><strong>Selected Seats:</strong> <?php echo htmlspecialchars($selectedSeats); ?></p>
            <p><strong>Total Amount:</strong> Rs. <?php echo htmlspecialchars($totalAmount); ?></p>
            <p><strong>From:</strong> <?php echo htmlspecialchars($_SESSION['from_location']); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($_SESSION['to_location']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($_SESSION['date']); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($_SESSION['time']); ?></p>
        </div>

        <div class="qr-container">
            <img src="upi_1735747723168.png" alt="UPI QR Code" class="qr-code">
            <div class="upi-id">UPI ID: onlineticketreservation@ybl</div>
        </div>

        <div class="payment-instructions">
            <h3>Payment Instructions:</h3>
            <ol>
                <li>Open your UPI payment app (Google Pay, PhonePe, Paytm, etc.)</li>
                <li>Scan the QR code or enter the UPI ID manually</li>
                <li>Enter the exact amount: Rs. <?php echo htmlspecialchars($totalAmount); ?></li>
                <li>Complete the payment</li>
                <li>Click on "Confirm Payment" below after payment is done</li>
            </ol>
        </div>

        <form action="generate_ticket.php" method="POST">
            <input type="hidden" name="seats" value="<?php echo htmlspecialchars($selectedSeats); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($totalAmount); ?>">
            <button type="submit" class="confirm-button">Confirm Payment</button>
        </form>
    </div>
</body>
</html>

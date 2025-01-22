<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle ticket cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];
    
    // Add your cancellation logic here
    $sql = "UPDATE tickets SET status = 'cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $ticket_id, $user_id);
    $seatSql = "UPDATE seats SET status = 'available', ticket_id = NULL WHERE ticket_id = ?";
    $seatStmt = $conn->prepare($seatSql);
    $seatStmt->bind_param("i", $ticket_id);
    
    if ($stmt->execute()) {
       if ($seatStmt->execute()) {
            // Commit the transaction if all queries succeed
            $conn->commit();



       }
        $message = "Ticket cancelled successfully! Refund will be processed within 3-5 business days.";
    } else {
        $message = "Error cancelling ticket. Please try again.";
    }
}

// Get user's active tickets
$sql = "SELECT * FROM tickets WHERE user_id = ? AND status = 'active' ORDER BY booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Ticket - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cancel-ticket-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 2rem;
        }

        .ticket-list {
            margin-top: 2rem;
        }

        .ticket-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-info h3 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .ticket-info p {
            color: #666;
            margin: 0.2rem 0;
        }

        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .confirm-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .modal-buttons {
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
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

    <div class="cancel-ticket-container">
        <h2>Cancel Ticket</h2>
        
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="ticket-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($ticket = $result->fetch_assoc()): ?>
                    <div class="ticket-card">
                        <div class="ticket-info">
                            <h3>Ticket #<?php echo $ticket['id']; ?></h3>
                            <p><strong>From:</strong> <?php echo htmlspecialchars($ticket['source']); ?></p>
                            <p><strong>To:</strong> <?php echo htmlspecialchars($ticket['destination']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($ticket['travel_date'])); ?></p>
                            <p><strong>Amount:</strong> ₹<?php echo number_format($ticket['amount'], 2); ?></p>
                        </div>
                        <button class="btn btn-danger" onclick="showConfirmModal(<?php echo $ticket['id']; ?>)">
                            Cancel Ticket
                        </button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No active tickets found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="confirm-modal" id="confirmModal">
        <div class="modal-content">
            <h3>Confirm Cancellation</h3>
            <p>Are you sure you want to cancel this ticket? This action cannot be undone.</p>
            <div class="modal-buttons">
                <button class="btn" onclick="hideConfirmModal()">No, Keep Ticket</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="ticket_id" id="cancelTicketId">
                    <button type="submit" class="btn btn-danger">Yes, Cancel Ticket</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showConfirmModal(ticketId) {
            document.getElementById('confirmModal').style.display = 'flex';
            document.getElementById('cancelTicketId').value = ticketId;
        }

        function hideConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
    </script>
</body>
</html>

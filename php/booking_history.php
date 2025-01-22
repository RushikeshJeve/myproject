<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all bookings for the current user
$userId = $_SESSION['user_id'];
$sql = "SELECT t.*, GROUP_CONCAT(s.seat_number) as seats 
        FROM tickets t 
        LEFT JOIN seats s ON t.id = s.ticket_id 
        WHERE t.user_id = ? 
        GROUP BY t.id 
        ORDER BY t.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .history-container {
            max-width: 1000px;
            margin: 100px auto;
            padding: 2rem;
        }

        .booking-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .booking-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .booking-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-completed {
            background: #cce5ff;
            color: #004085;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .booking-body {
            padding: 1.5rem;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            padding: 0.5rem;
        }

        .detail-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .detail-value {
            color: #333;
            font-weight: bold;
        }

        .booking-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .action-button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .download-btn {
            background: #007bff;
            color: white;
        }

        .download-btn:hover {
            background: #0056b3;
        }

        .cancel-btn {
            background: #dc3545;
            color: white;
        }

        .cancel-btn:hover {
            background: #c82333;
        }

        .no-bookings {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .booking-details {
                grid-template-columns: 1fr;
            }
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

    <div class="history-container">
        <h1>Booking History</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($booking = $result->fetch_assoc()): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div>
                            <strong>Booking ID:</strong> <?php echo str_pad($booking['id'], 8, '0', STR_PAD_LEFT); ?>
                        </div>
                        <div class="booking-status status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </div>
                    </div>
                    <div class="booking-body">
                        <div class="booking-details">
                            <div class="detail-item">
                                <div class="detail-label">From</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['source']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">To</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['destination']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Travel Date</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['travel_date']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Travel Time</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['travel_time']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Seats</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['seats']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Amount</div>
                                <div class="detail-value">Rs. <?php echo htmlspecialchars($booking['amount']); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Booking Date</div>
                                <div class="detail-value"><?php echo date('d M Y, h:i A', strtotime($booking['booking_date'])); ?></div>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <?php if ($booking['status'] === 'active'): ?>
                                <form action="regenerate_ticket.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <button type="submit" class="action-button download-btn">
                                        <i class="fas fa-download"></i> Download Ticket
                                    </button>
                                </form>
                                
                                <?php
                                // Only show cancel button if travel date is in future
                                $travelDate = strtotime($booking['travel_date']);
                                $today = strtotime(date('Y-m-d'));
                                if ($travelDate > $today):
                                ?>
                                    <form action="cancel_ticket.php" method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" class="action-button cancel-btn">
                                            <i class="fas fa-times"></i> Cancel Booking
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-bookings">
                <i class="fas fa-ticket-alt" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <h3>No Bookings Found</h3>
                <p>You haven't made any bookings yet.</p>
                <a href="book_ticket.php" class="action-button download-btn" style="margin-top: 1rem;">
                    Book a Ticket
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

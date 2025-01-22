<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['booking_id'])) {
    header("Location: dashboard.php");
    exit();
}

$bookingId = $_POST['booking_id'];
$userId = $_SESSION['user_id'];

// Fetch booking details
$sql = "SELECT t.*, GROUP_CONCAT(s.seat_number) as seats, u.name as user_name 
        FROM tickets t 
        LEFT JOIN seats s ON t.id = s.ticket_id 
        LEFT JOIN users u ON t.user_id = u.id 
        WHERE t.id = ? AND t.user_id = ? 
        GROUP BY t.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: booking_history.php");
    exit();
}

$booking = $result->fetch_assoc();

// Generate ticket content
$ticketContent = "=== ONLINE TICKET BOOKING - E-TICKET ===\n\n";
$ticketContent .= "Ticket ID: " . str_pad($booking['id'], 8, '0', STR_PAD_LEFT) . "\n";
$ticketContent .= "Booking Date: " . $booking['booking_date'] . "\n\n";
$ticketContent .= "Passenger Details:\n";
$ticketContent .= "Name: " . $booking['user_name'] . "\n\n";
$ticketContent .= "Journey Details:\n";
$ticketContent .= "From: " . $booking['source'] . "\n";
$ticketContent .= "To: " . $booking['destination'] . "\n";
$ticketContent .= "Date: " . $booking['travel_date'] . "\n";
$ticketContent .= "Time: " . $booking['travel_time'] . "\n";
$ticketContent .= "Seats: " . $booking['seats'] . "\n\n";
$ticketContent .= "Amount Paid: Rs. " . $booking['amount'] . "\n\n";
$ticketContent .= "=== Important Instructions ===\n";
$ticketContent .= "1. Please carry a valid ID proof while traveling\n";
$ticketContent .= "2. Reach the boarding point 30 minutes before departure\n";
$ticketContent .= "3. This is an electronic ticket, please take a printout\n\n";
$ticketContent .= "Thank you for choosing our service!\n";
$ticketContent .= "=================================";

// Set headers for file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="ticket_' . str_pad($booking['id'], 8, '0', STR_PAD_LEFT) . '.txt"');
header('Content-Length: ' . strlen($ticketContent));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $ticketContent;

// Add JavaScript to redirect after download
echo "<script>
    window.onload = function() {
        setTimeout(function() {
            window.location.href = 'booking_history.php';
        }, 1000);
    }
</script>";
exit;

<?php
session_start();

if (!isset($_SESSION['ticket_content'])) {
    header("Location: dashboard.php");
    exit();
}

$ticketContent = $_SESSION['ticket_content'];

// Set headers for file download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="ticket.txt"');
header('Content-Length: ' . strlen($ticketContent));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

echo $ticketContent;

// Clear the ticket content from session
unset($_SESSION['ticket_content']);

// Add JavaScript to redirect after download
echo "<script>
    window.onload = function() {
        setTimeout(function() {
            window.location.href = 'dashboard.php';
        }, 1000);
    }
</script>";
exit;

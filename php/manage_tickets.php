<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch tickets data from the database
$query = "
SELECT
    tickets.id,
    users.name AS user_name,
    tickets.source,
    tickets.destination,
    tickets.travel_date,
    tickets.travel_time,
    tickets.booking_date,
    tickets.amount,
    seats.seat_number,
    tickets.status
FROM
    tickets
JOIN
    users ON tickets.user_id = users.id
JOIN
    seats ON tickets.id = seats.ticket_id
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .manage-tickets-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 1rem;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="manage-tickets-container">
        <h1>Manage Tickets</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Travel Date</th>
                    <th>Travel Time</th>
                    <th>Booking Date</th>
                    <th>Amount</th>
                    <th>Seat Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['source']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['destination']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['travel_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['travel_time']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['seat_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No tickets found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

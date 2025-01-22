<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Function to fetch data from a table
function fetchData($conn, $query) {
    $result = $conn->query($query);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Fetch users data
$users = fetchData($conn, "SELECT * FROM users");

// Fetch tickets data
$tickets = fetchData($conn, "SELECT * FROM tickets");

// Fetch admins data
$admins = fetchData($conn, "SELECT * FROM admins");

// Fetch seats data
$seats = fetchData($conn, "SELECT * FROM seats");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .settings-container {
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

        .section-title {
            margin: 2rem 0 1rem;
            font-size: 1.5rem;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h1>Settings</h1>

        <!-- Users Table -->
        <h2 class="section-title">Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Tickets Table -->
        <h2 class="section-title">Tickets</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Travel Date</th>
                    <th>Travel Time</th>
                    <th>Booking Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['source']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['destination']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['travel_date']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['travel_time']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['amount']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Admins Table -->
        <h2 class="section-title">Admins</h2>
        <table>
            <thead>
                <tr>
                    <th>Admin ID</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($admin['admin_id']); ?></td>
                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Seats Table -->
        <h2 class="section-title">Seats</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Seat Number</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Ticket ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seats as $seat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($seat['id']); ?></td>
                        <td><?php echo htmlspecialchars($seat['seat_number']); ?></td>
                        <td><?php echo htmlspecialchars($seat['travel_date']); ?></td>
                        <td><?php echo htmlspecialchars($seat['status']); ?></td>
                        <td><?php echo htmlspecialchars($seat['ticket_id']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

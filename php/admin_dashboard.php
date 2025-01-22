<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 2rem;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            color: #333;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .dashboard-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .action-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
        }

        .action-icon {
            font-size: 2.5rem;
            color: #007bff;
            margin-bottom: 1rem;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .action-card:last-child .action-icon {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($admin_username); ?>!</h1>
            <a href="logoutadmin.php" class="logout-btn">Logout</a>
        </div>

        <section class="dashboard-actions">
            <div class="action-card">
                <i class="fas fa-users action-icon"></i>
                <h3>Manage Users</h3>
                <p>View and manage all registered users</p>
                <a href="manage_users.php" class="btn">Manage Users</a>
            </div>
            <div class="action-card">
                <i class="fas fa-ticket-alt action-icon"></i>
                <h3>Manage Tickets</h3>
                <p>View and manage all booked tickets</p>
                <a href="manage_tickets.php" class="btn">Manage Tickets</a>
            </div>
            <div class="action-card">
                <i class="fas fa-cogs action-icon"></i>
                <h3>View Reports</h3>
                <p>Manage site and reports and analytics</p>
                <a href="settings.php" class="btn">View Reports</a>
            </div>
            <div class="action-card">
                <i class="fas fa-ban action-icon"></i>
                <h3>DROP</h3>
                <p>reset database</p>
                <a href="view_reports.php" class="btn btn-danger">Reset database</a>
            </div>
        </section>
    </div>
</body>
</html>

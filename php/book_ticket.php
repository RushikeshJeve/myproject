<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .booking-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 2rem;
        }

        .booking-form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
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

    <div class="booking-container">
        <h2>Book Your Ticket</h2>
        
        <form class="booking-form" action="select_seats.php" method="POST">
            <div class="form-group">
                <label for="from">From</label>
                <select id="from" name="from" required>
                    <option value="">Select Source City</option>
                
                    <option value="pune">Pune</option>
                    
                </select>
            </div>

            <div class="form-group">
                <label for="to">To</label>
                <select id="to" name="to" required>
                    <option value="">Select Destination City</option>
                    <option value="Mumbai">Mumbai</option>
                   
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                <label for="date">Date of Journey</label>
                <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="time">Preferred Time</label>
                    <input type="time" id="time" name="time" required>
                </div>
            </div>

            <button type="submit" class="btn-primary">Next</button>
        </form>
    </div>

    <script>
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];

        // Prevent selecting same city for source and destination
        document.getElementById('to').addEventListener('change', function() {
            const fromSelect = document.getElementById('from');
            if (this.value === fromSelect.value) {
                alert('Source and destination cannot be the same!');
                this.value = '';
            }
        });

        document.getElementById('from').addEventListener('change', function() {
            const toSelect = document.getElementById('to');
            if (this.value === toSelect.value) {
                alert('Source and destination cannot be the same!');
                this.value = '';
            }
        });
        const today = new Date();
    const tomorrow = new Date(today);
    const dayAfterTomorrow = new Date(today);

    // Set the next two days
    tomorrow.setDate(today.getDate() + 1);
    dayAfterTomorrow.setDate(today.getDate() + 2);

    // Format dates to yyyy-mm-dd
    const formatDate = (date) => date.toISOString().split('T')[0];

    // Set min and max attributes for the date input
    const dateInput = document.getElementById('date');
    dateInput.min = formatDate(today);
    dateInput.max = formatDate(dayAfterTomorrow);

    // Prevent selecting the same city for source and destination
    document.getElementById('to').addEventListener('change', function() {
        const fromSelect = document.getElementById('from');
        if (this.value === fromSelect.value) {
            alert('Source and destination cannot be the same!');
            this.value = '';
        }
    });

    document.getElementById('from').addEventListener('change', function() {
        const toSelect = document.getElementById('to');
        if (this.value === toSelect.value) {
            alert('Source and destination cannot be the same!');
            this.value = '';
        }
    });
    </script>
</body>
</html>

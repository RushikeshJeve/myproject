<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Store form data in session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['from_location'] = $_POST['from'];
    $_SESSION['to_location'] = $_POST['to'];
    $_SESSION['date'] = $_POST['date'];
    $_SESSION['time'] = $_POST['time'];
    $_SESSION['booking_time'] = date('Y-m-d H:i:s');
}

// Initialize seats array (20 seats: A1-A10, B1-B10)
$seats = [];
for ($i = 1; $i <= 10; $i++) {
    // A row seats
    $seatId = 'A' . $i;
    $sql = "SELECT status FROM seats WHERE seat_number = ? AND travel_date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $seatId, $_SESSION['date']);
    $stmt->execute();
    $result = $stmt->get_result();
    $status = $result->num_rows > 0 ? $result->fetch_assoc()['status'] === 'booked' : false;
    $seats[] = ['seat_id' => $seatId, 'status' => $status];

    // B row seats
    $seatId = 'B' . $i;
    $stmt->bind_param("ss", $seatId, $_SESSION['date']);
    $stmt->execute();
    $result = $stmt->get_result();
    $status = $result->num_rows > 0 ? $result->fetch_assoc()['status'] === 'booked' : false;
    $seats[] = ['seat_id' => $seatId, 'status' => $status];
}

$_SESSION['seats'] = $seats;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 2rem;
        }

        .summary-container {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .summary-container h3 {
            margin-top: 0;
            color: #333;
        }

        .summary-container p {
            margin: 0.5rem 0;
            color: #666;
        }

        .button-container {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 0.5rem;
            margin: 2rem 0;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }

        .button-container::before {
            content: 'Bus Front';
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .button-container button {
            aspect-ratio: 1;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .button-container button:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }

        #selected-sites {
            min-height: 30px;
            margin: 1rem 0;
            font-weight: bold;
            color: #007bff;
        }

        #make-payment-button {
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

        #make-payment-button:hover {
            background: #218838;
        }

        .row-label {
            position: absolute;
            left: -30px;
            color: #666;
            font-weight: bold;
        }

        .seats-row {
            position: relative;
            display: contents;
        }

        .seats-row:nth-child(1) .row-label {
            top: 10px;
        }

        .seats-row:nth-child(2) .row-label {
            top: 50px;
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

    <div class="container">
        <h1>Select Seats</h1>

        <div class="summary-container">
            <h3>Journey Details:</h3>
            <p><strong>From:</strong> <?php echo htmlspecialchars($_SESSION['from_location']); ?></p>
            <p><strong>To:</strong> <?php echo htmlspecialchars($_SESSION['to_location']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($_SESSION['date']); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($_SESSION['time']); ?></p>
            <p><strong>Booking Time:</strong> <?php echo htmlspecialchars($_SESSION['booking_time']); ?></p>
        </div>

        <div class="button-container">
            <div class="seats-row">
                <span class="row-label">A</span>
                <?php
                $occupiedCount = 0;
                foreach ($seats as $index => $seat) {
                    if ($index < 10) { // A row seats
                        $seatId = $seat['seat_id'];
                        $status = $seat['status'];
                        $color = $status ? "#ff0000" : "#007bff"; // Red for occupied, blue for available
                        $disabled = $status ? 'disabled' : '';
                        if ($status) $occupiedCount++;
                        echo "<button value='$seatId' onclick='selectSite(this)' style='background-color: $color;' $disabled>$seatId</button>";
                    }
                }
                ?>
            </div>
            <div class="seats-row">
                <span class="row-label">B</span>
                <?php
                foreach ($seats as $index => $seat) {
                    if ($index >= 10) { // B row seats
                        $seatId = $seat['seat_id'];
                        $status = $seat['status'];
                        $color = $status ? "#ff0000" : "#007bff"; // Red for occupied, blue for available
                        $disabled = $status ? 'disabled' : '';
                        if ($status) $occupiedCount++;
                        echo "<button value='$seatId' onclick='selectSite(this)' style='background-color: $color;' $disabled>$seatId</button>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="summary-container">
            <h3>Selected Seats:</h3>
            <div id="selected-sites"></div>
            <h3>Total Cost: Rs <span id="total-cost">0</span></h3>
            <h3>Total Occupied Seats: <span id="occupied-count"><?php echo $occupiedCount; ?></span></h3>
            <button id="make-payment-button" onclick="makePayment()">Make Payment</button>
        </div>
    </div>

    <script>
        const selectedSites = new Set();
        const siteCost = 300;

        function selectSite(button) {
            if (selectedSites.has(button.value)) {
                selectedSites.delete(button.value);
                button.style.backgroundColor = "#007bff"; // Reset to blue for deselected
            } else {
                if (selectedSites.size >= 4) {
                    alert('You can only select up to 4 seats at a time.');
                    return;
                }
                selectedSites.add(button.value);
                button.style.backgroundColor = "#00ff00"; // Green for selected seats
            }
            updateSummary();
        }

        function updateSummary() {
            const selectedSitesDiv = document.getElementById('selected-sites');
            selectedSitesDiv.innerHTML = Array.from(selectedSites).join(', ');
            const totalCost = selectedSites.size * siteCost;
            document.getElementById('total-cost').textContent = totalCost;
            
            // Enable/disable payment button based on selection
            const paymentButton = document.getElementById('make-payment-button');
            paymentButton.disabled = selectedSites.size === 0;
        }

        function makePayment() {
            if (selectedSites.size === 0) {
                alert('Please select at least one seat.');
                return;
            }

            const selectedSiteNames = Array.from(selectedSites).join(',');
            const totalCost = selectedSites.size * siteCost;

            // Store selected seats in URL parameters
            const paymentUrl = `payment.php?seats=${selectedSiteNames}&total=${totalCost}`;
            window.location.href = paymentUrl;
        }

        // Initialize payment button state
        document.getElementById('make-payment-button').disabled = true;
    </script>
</body>
</html>

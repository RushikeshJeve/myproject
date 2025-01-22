<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Online Ticket Booking</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Additional Dashboard Styles */
        .welcome-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../images/hero-bg.jpg');
            background-size: cover;
            padding: 2rem;
            color: white;
            text-align: center;
            margin-top: 60px;
        }

        .user-info {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
        }

        .user-info span {
            color: white;
            margin-right: 1rem;
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
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
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
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand">
            <a href="dashboard.php">Ticket Booking</a>
        </div>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#cities">Cities</a></li>
            <li><a href="#help">Help</a></li>
            <li class="user-info">
                <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <h1>Welcome to Your Dashboard</h1>
        <p>Book tickets, manage your bookings, and explore destinations</p>
    </section>

    <!-- Dashboard Actions -->
    <section class="dashboard-actions">
        <div class="action-card">
            <i class="fas fa-ticket-alt action-icon"></i>
            <h3>Book Tickets</h3>
            <p>Search and book tickets for your next journey</p>
            <a href="book_ticket.php" class="btn">Book Now</a>
        </div>
        <div class="action-card">
            <i class="fas fa-history action-icon"></i>
            <h3>Booking History</h3>
            <p>View and manage your previous bookings</p>
            <a href="booking_history.php" class="btn">View History</a>
        </div>
        <div class="action-card">
            <i class="fas fa-ban action-icon"></i>
            <h3>Cancel Ticket</h3>
            <p>Cancel your booked tickets and get refund</p>
            <a href="cancel_ticket.php" class="btn btn-danger">Cancel Ticket</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2>Our Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h3>Easy Booking</h3>
                <p>Book your tickets in just a few clicks</p>
            </div>
            <div class="feature-card">
                <h3>Secure Payments</h3>
                <p>Safe and secure payment options</p>
            </div>
            <div class="feature-card">
                <h3>24/7 Support</h3>
                <p>Round the clock customer support</p>
            </div>
        </div>
    </section>

    <!-- Cities Section -->
    <section id="cities" class="cities-section">
        <h2>travel with us</h2>
        <div class="cities-container">
            <button class="scroll-btn scroll-left" id="scrollLeft">
                <i class="arrow left"></i>
            </button>
            <div class="cities-scroll">
                <div class="cities-wrapper">
                    <div class="city-card">
                        <img src="../images/cities/mumbai.jpg" alt="Mumbai">
                        <div class="city-info">
                            <h3>Mumbai</h3>
                            <p>The City of Dreams</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/delhi.jpg" alt="Delhi">
                        <div class="city-info">
                            <h3>Delhi</h3>
                            <p>The Heart of India</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/bangalore.jpg" alt="Bangalore">
                        <div class="city-info">
                            <h3>Bangalore</h3>
                            <p>Silicon Valley of India</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/hyderabad.jpg" alt="Hyderabad">
                        <div class="city-info">
                            <h3>Hyderabad</h3>
                            <p>City of Pearls</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/chennai.jpg" alt="Chennai">
                        <div class="city-info">
                            <h3>Chennai</h3>
                            <p>Gateway of South India</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/kolkata.png" alt="Kolkata">
                        <div class="city-info">
                            <h3>Kolkata</h3>
                            <p>City of Joy</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/jaipur.jpg" alt="Jaipur">
                        <div class="city-info">
                            <h3>Jaipur</h3>
                            <p>Pink City</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/ahmedabad.jpg" alt="Ahmedabad">
                        <div class="city-info">
                            <h3>Ahmedabad</h3>
                            <p>Manchester of India</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/pune.jpg" alt="Pune">
                        <div class="city-info">
                            <h3>Pune</h3>
                            <p>Oxford of the East</p>
                        </div>
                    </div>
                    <div class="city-card">
                        <img src="../images/cities/agra.jpg" alt="Agra">
                        <div class="city-info">
                            <h3>Agra</h3>
                            <p>City of Taj</p>
                        </div>
                    </div>
                </div>
            </div>
       
                    <!-- Repeat other city cards with updated image paths -->
                </div>
            </div>
            <button class="scroll-btn scroll-right" id="scrollRight">
                <i class="arrow right"></i>
            </button>
        </div>
    </section>

    <!-- Help Section -->
    <section id="help" class="help-section">
        <h2>Need Help?</h2>
        <div class="help-content">
            <div class="help-grid">
                <div class="help-card">
                    <h3>FAQs</h3>
                    <p>Find answers to commonly asked questions</p>
                    <a href="#" class="btn">View FAQs</a>
                </div>
                <div class="help-card">
                    <h3>Contact Support</h3>
                    <p>Get in touch with our support team</p>
                    <a href="#" class="btn">Contact Us</a>
                </div>
                <div class="help-card">
                    <h3>User Guide</h3>
                    <p>Learn how to use our platform</p>
                    <a href="#" class="btn">Read Guide</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 Online Ticket Booking. All rights reserved.</p>
    </footer>

    <script src="../js/script.js"></script>
</body>
</html>

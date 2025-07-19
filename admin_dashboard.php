<?php
// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get count of vacancies, applications, events, registrations, and users
$vacancy_count = $conn->query("SELECT COUNT(*) AS count FROM vacancies")->fetch_assoc()['count'];
$application_count = $conn->query("SELECT COUNT(*) AS count FROM applications")->fetch_assoc()['count'];
$event_count = $conn->query("SELECT COUNT(*) AS count FROM events")->fetch_assoc()['count'];
$registration_count = $conn->query("SELECT COUNT(*) AS count FROM event_registered")->fetch_assoc()['count'];
$user_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count']; // Assuming the table name is 'users'

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacancy Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css"> <!-- Custom CSS file -->
    <style>
    /* Card Styling */
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .card {
        flex: 1;
        max-width: 300px;
        padding: 20px;
        background: linear-gradient(135deg, #ffffff, #f4f7f8);
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-align: center;
        transition: all 0.3s;
        position: relative;
        cursor: pointer;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
    }

    .card i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: black;
        animation: bounce 2s infinite;
    }

    .card h5 {
        font-size: 2rem;
        margin: 10px 0;
    }

    .card p {
        color: #666;
        margin-bottom: 15px;
    }

    .card button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: black;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .card button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>
<?php 
  include "admin_nevbar.php"; // Include the navigation bar
?>

<div class="content flex-grow-1 p-4 ">

    <div class="card-container mt-5  pt-5">
        <!-- Vacancy Management Card -->
        <div class="card vacancies" onclick="location.href='vacancies.php';">
            <i class="bi bi-briefcase-fill text-dark"></i>
            <h5><?php echo $vacancy_count; ?></h5>
            <p>Vacancies</p>
            <button>View Vacancies</button>
        </div>
        
        <!-- Application Management Card -->
        <div class="card applications" onclick="location.href='applications.php';">
            <i class="bi bi-envelope-fill text-dark"></i>
            <h5><?php echo $application_count; ?></h5>
            <p>Applications</p>
            <button>View Applications</button>
        </div>
        
        <!-- Events Management Card -->
        <div class="card events" onclick="location.href='events.php';">
            <i class="bi bi-calendar-event-fill text-dark"></i>
            <h5><?php echo $event_count; ?></h5>
            <p>Events</p>
            <button>View Events</button>
        </div>
        
        <!-- Event Registrations Card -->
        <div class="card registrations" onclick="location.href='event_registrations.php';">
            <i class="bi bi-person-check-fill text-dark"></i>
            <h5><?php echo $registration_count; ?></h5>
            <p>Event Registrations</p>
            <button>View Registrations</button>
        </div>

        <!-- User Management Card -->
        <div class="card user-management" onclick="location.href='user_profile.php';">
            <i class="bi bi-person-fill text-dark"></i>
            <h5><?php echo $user_count; ?></h5>
            <p>User Management</p>
            <button>View Users</button>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

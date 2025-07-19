<?php
include 'config.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$logged_in_company_id = $_SESSION['company_id'];

$stmt = $conn->prepare("SELECT company_name FROM Company WHERE company_id = ?");
$stmt->bind_param("i", $logged_in_company_id);
$stmt->execute();
$stmt->bind_result($company_name);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        body {
            background: linear-gradient(to right, #e3f2fd, #fff3e0);
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 15px;
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar {
            background-color: #333;
            color: #eee;
            width: 250px;
            height: 100vh;
            padding: 20px;
            position: fixed;
            transition: all 0.3s ease-in-out;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2);
        }
        .sidebar h4 {
            color: #ff79c6;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }
        .sidebar a {
            color: #f8f8f2;
            text-decoration: none;
            display: block;
            padding: 12px 10px;
            border-radius: 5px;
            margin-bottom: 0.5rem;
            transition: background-color 0.3s;
            font-size: 1.1rem;
        }
        .sidebar a:hover {
            background-color: #44475a;
            color: #50fa7b;
        }

        /* Content */
        .content {
            margin-left: 270px;
            padding: 40px;
        }
        .content h1 {
            font-size: 2.5rem;
            color: #1a73e8;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .content p {
            font-size: 1.2rem;
            color: #5f6368;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <span>Company Dashboard</span>
        <span><?php echo htmlspecialchars($company_name); ?></span> 
    </div>

    <div class="d-flex">
        <div class="sidebar">
            <a href="company_dashboard.php">Company Dashboard</a>
            <a href="c_vacancies.php">Vacancy Management</a>
            <a href="c_applications.php">Application Management</a>
            <a href="index.php">Logout</a>
        </div>
        
        <div class="content">
            
        </div>
    </div>

</body>
</html>

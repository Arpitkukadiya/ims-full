<?php
session_start();

$company_id = isset($_SESSION['company_id']) ? $_SESSION['company_id'] : 0;

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($company_id > 0) {
   
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count 
        FROM vacancies 
        WHERE company_id = ?
    ");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $stmt->bind_result($vacancy_count);
    $stmt->fetch();
    $stmt->close();

    
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS count 
        FROM applications 
        WHERE company_id = ?
    ");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $stmt->bind_result($application_count);
    $stmt->fetch();
    $stmt->close();
} else {
    $vacancy_count = $application_count = 0;
    echo "Invalid company ID or user not logged in.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
   
    body {
        background-color: #f4f6f8; 
        font-family: 'Arial', sans-serif;
    }

    
    .welcome-message {
        font-size: 1.8rem;
        color: #1a73e8;
        margin: 20px 0;
        font-weight: bold;
        text-align: center;
    }

   
    .card-container {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

  
    .card {
        flex: 1;
        max-width: 300px;
        padding: 40px 20px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e3f2fd, #ffebee);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
        text-align: center;
        color: #1a73e8;
        font-size: 1.5rem;
        font-weight: bold;
        position: relative;
        border: 1px solid #ddd;
    }

    
    .card.vacancies { border-left: 5px solid #34a853; }
    .card.applications { border-left: 5px solid #ea4335; }

   
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
        background-color: #f1f3f4; 
    }

    .card h5 {
        font-size: 3rem;
        color: #202124;
        margin-bottom: 15px;
        transition: color 0.3s;
    }

    .card:hover h5 {
        color: #1a73e8;
    }

    .icon {
        font-size: 3rem;
        color: #34a853;
        margin-bottom: 10px;
        animation: iconBounce 1s ease-in-out infinite alternate;
    }

    .icon.applications {
        color: #ea4335; 
    }

    @keyframes iconBounce {
        from { transform: translateY(0); }
        to { transform: translateY(-10px); }
    }

    nav {
        background-color: #003366; 
    }

    .navbar-brand, .nav-link {
        color: #ffffff !important;
    }

    .navbar-brand:hover, .nav-link:hover {
        color: #cce7ff !important; 
    }

    footer {
        margin-top: 40px;
        text-align: center;
        padding: 10px;
        background-color: #003366; 
        color: #ffffff; 
    }
</style></head>
<body>
  
<?php
    include "comp_nav.php";
    ?>
        <div class="content">

            <div class="card-container">
                <div class="card vacancies">
                    <i class="bi bi-briefcase-fill icon"></i>
                    <h5><?php echo $vacancy_count; ?></h5>
                    <p>Vacancies</p>
                </div>
                <div class="card applications">
                    <i class="bi bi-envelope-fill icon applications"></i>
                    <h5><?php echo $application_count; ?></h5>
                    <p>Applications</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

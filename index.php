<?php
session_start();

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

  
    $student_sql = "SELECT * FROM users WHERE email = '$email'";
    $student_result = $conn->query($student_sql);

    
    $company_sql = "SELECT * FROM Company WHERE email = '$email'";
    $company_result = $conn->query($company_sql);

    $admin_sql = "SELECT * FROM admin WHERE email = '$email'";
    $admin_result = $conn->query($admin_sql);

    if ($student_result->num_rows > 0) {
        $row = $student_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            header("Location: student_dashboard.php");
            exit();
        } else {
            echo "Invalid password for student.";
        }
    }
    elseif ($company_result->num_rows > 0) {
        $row = $company_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['company_id'] = $row['company_id'];
            header("Location: company_dashboard.php");
            exit();
        } else {
            echo "Invalid password for company.";
        }
    }
    elseif ($admin_result->num_rows > 0) {
        $row = $admin_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Invalid password for admin.";
        }
    }
    else {
        echo "User not found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f8f9fa;
        }
        .login-form {
            max-width: 450px;
            margin: 60px auto;
            padding: 30px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-form h2 {
            font-size: 2rem;
            color: #343a40;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .login-form label {
            font-size: 1rem;
            color: #495057;
            font-weight: bold;
        }
        .login-form .form-control {
            padding: 10px;
            border-radius: 6px;
        }
        .login-form .btn-primary {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            background-color: #007bff;
        }
        .login-form .btn-primary:hover {
            background-color: #0056b3;
        }
        .register-links {
            text-align: center;
            margin-top: 25px;
            font-size: 0.95rem;
            color: #343a40;
        }
        .register-links a {
            display: inline-block;
            margin: 5px 0;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .register-links a:hover {
            text-decoration: underline;
            color: #0056b3;
        }
        .register-links h2 {
            font-size: 1.5rem;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form action="index.php" method="POST" class="login-form">
        <h2>Login</h2>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>

        <div class="register-links mt-4">
            <h2>Sign Up</h2>
            <a href="student_register.php" class="btn btn-link">jobseeker Register</a>
            <a href="company_register.php" class="btn btn-link">Company Register</a>
            <a href="admin_register.php" class="btn btn-link">Admin Register</a>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

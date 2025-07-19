<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        form {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 1.8rem;
            color: #343a40;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        label {
            font-size: 1rem;
            color: #495057;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff; 
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .sign-in-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 1rem;
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }
        .sign-in-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form action="student_register.php" method="POST" id="registrationForm">
        <h2>Student Registration</h2>
        <label for="name">Full Name</label>
        <input type="text" name="name" class="form-control" required>

        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" required>

        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" required>

        <input type="submit" value="Register" class="btn btn-primary mt-3">

        <a href="index.php" class="sign-in-link">Sign In</a>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const email = document.querySelector('[name="email"]').value;
            const password = document.querySelector('[name="password"]').value;

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                event.preventDefault();
            }

            if (!passwordPattern.test(password)) {
                alert('Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>

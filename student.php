<?php

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "campus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_jobs = "SELECT * FROM jobs";
$result_jobs = $conn->query($sql_jobs);

$sql_workshops = "SELECT * FROM workshops";
$result_workshops = $conn->query($sql_workshops);



if (isset($_POST['apply_vacancy'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $resume = $_POST['resume'];
    $cover_letter = $_POST['cover_letter'];
    $vacancy_id = $_POST['vacancy_id'];

    $sql = "INSERT INTO application (name, email, resume, cover_letter, vacancy_id) VALUES ('$name', '$email', '$resume', '$cover_letter', '$vacancy_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

if (isset($_POST['register_workshop'])) {
    $title = $_POST['title'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO event (title, name, email, phone) VALUES ('$title', '$name', '$email', '$phone')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
            font-family: 'Arial', sans-serif;
            padding-top: 56px; 
            margin-bottom: 60px; 
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .card-title {
            font-size: 1.5rem;
            color: #343a40;
        }
        .card-text {
            color: #6c757d;
        }
        .btn {
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 10px;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .section-title {
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
            color: #343a40;
        }
        .container {
            margin-top: 20px;
        }
        .text-center p {
            font-size: 1.2rem;
            color: #6c757d;
        }
        footer {
            background-color: #343a40;
            color: #fff;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
        }
        header {
            background-color: #343a40;
            color: #fff;
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        .header-hidden {
            transform: translateY(-100%);
        }
        .footer-hidden {
            transform: translateY(100%);
        }
    </style>
</head>
<body>

    <header id="header">
        <h1>Student Dashboard</h1>
    </header>

    <div class="container">
        <div class="text-center mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <h2 class="section-title">Job Listings</h2>
        <div class="row">
            <?php
            if ($result_jobs->num_rows > 0) {
                while ($job = $result_jobs->fetch_assoc()) {
                    echo "<div class='col-md-4'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$job['title']}</h5>
                                    <p class='card-text'>{$job['description']}</p>
                                    <a href='apply_job.php?job_id={$job['id']} class='btn btn-primary' data-toggle='modal' data-target='#applyModal'>Apply Now</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p class='text-center'>No job listings available.</p>";
            }
            ?>
        </div>

        <h2 class="section-title">Workshops</h2>
        <div class="row">
            <?php
            if ($result_workshops->num_rows > 0) {
                while ($workshop = $result_workshops->fetch_assoc()) {
                    echo "<div class='col-md-4'>
                            <div class='card'>
                                <div class='card-body'>
                                    <h5 class='card-title'>{$workshop['title']}</h5>
                                    <p class='card-text'>{$workshop['description']}</p>
                                    <a href='register_workshop.php?workshop_id={$workshop['id']}' class='btn btn-success' data-toggle='modal' data-target='#registerModal'>Register Now</a>
                               
                                    </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p class='text-center'>No workshops available.</p>";
            }
            ?>
        </div>
    </div>

<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">Apply for Vacancy</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="vacancy_id" value="1">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="resume">Resume</label>
                        <textarea class="form-control" name="resume" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cover_letter">Cover Letter</label>
                        <textarea class="form-control" name="cover_letter" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="apply_vacancy" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Workshop Registration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Workshop Title</label>
                        <input type="text" class="form-control" name="title" value="Web Development Workshop" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="register_workshop" class="btn btn-success">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <footer id="footer">
        <p>&copy; <?php echo date("Y"); ?> Campus Recruitment System. All rights reserved.</p>
    </footer>

    <script>
        let lastScrollTop = 0;
        const header = document.getElementById('header');
        const footer = document.getElementById('footer');

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

           
            if (scrollTop > lastScrollTop) {
                header.classList.add('header-hidden');
                footer.classList.remove('footer-hidden');
            } else {
                header.classList.remove('header-hidden');
                footer.classList.add('footer-hidden');
            }

            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>

</body>
</html>

<?php
$conn->close();
?>

<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$dbname = "campus";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    $student_sql = "SELECT name FROM users WHERE student_id = ?";
    $stmt = $conn->prepare($student_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($student_name);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: login.php");
    exit;
}
$vacancy_sql = "SELECT id, title, description, company, posted_date FROM vacancies";
$vacancy_result = $conn->query($vacancy_sql);

$workshop_sql = "SELECT id, title, description, date, location FROM events";
$workshop_result = $conn->query($workshop_sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $resume = $_FILES['resume']['name'] ?? '';
    $cover_page = $_POST['cover_page'] ?? '';

    if (empty($job_id) || empty($name) || empty($email) || empty($resume)) {
        $_SESSION['message'] = "Error: All fields are required for job application.";
        $_SESSION['message_type'] = "danger";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $company_sql = "SELECT v.company_id 
                        FROM vacancies v
                        INNER JOIN company c ON v.company_id = c.company_id 
                        WHERE v.id = '$job_id'";
        $company_result = $conn->query($company_sql);

        if ($company_result && $company_result->num_rows > 0) {
            $company_row = $company_result->fetch_assoc();
            $company_id = $company_row['company_id'];

            $sql = "INSERT INTO applications (vacancies_id, company_id, student_id, name, email, resume, cover_page)
                    VALUES ('$job_id', '$company_id', NULL, '$name', '$email', '$target_file', '$cover_page')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Application submitted successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: " . $conn->error;
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Error: No matching company found for the given job ID. Verify job ID and associated company.";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Error: Failed to upload resume.";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['workshop_id'])) {
    $workshop_id = $_POST['workshop_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $mobile = $_POST['mobile'] ?? '';

    if (empty($workshop_id) || empty($name) || empty($email) || empty($mobile)) {
        $_SESSION['message'] = "Error: All fields are required for workshop registration.";
        $_SESSION['message_type'] = "danger";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $sql = "INSERT INTO event_registered (event_id, name, email, mobile) 
            VALUES ('$workshop_id', '$name', '$email', '$mobile')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Workshop registration successful!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
    <style>
      
    body {
        background-color: #f0f8ff; 
        font-family: Arial, sans-serif;
    }

    header {
        margin-bottom: 20px;
    }

    .navbar {
        background-color: #003366; 
    }

    .navbar-brand {
        color: #ffffff !important;
    }

    .alert {
        margin-bottom: 20px;
    }

    .card {
        border: 1px solid #003366; 
        border-radius: 8px;
        transition: transform 0.2s; 
    }

    .card:hover {
        transform: scale(1.05); 
    }

    .card-title {
        color: #003366; 
    }

    .btn-primary {
        background-color: #0056b3; 
        border-color: #0056b3; 
    }

    .btn-primary:hover {
        background-color: #004494; 
        border-color: #004494; 
    }

    .modal-header {
        background-color: #003366; 
        color: #ffffff; 
    }

    .modal-body {
        background-color: #ffffff; 
    }

    .form-control {
        border: 1px solid #003366; 
    }

    .form-control:focus {
        border-color: #0056b3; 
        box-shadow: 0 0 5px rgba(0, 86, 179, 0.5);
    }

    footer {
        margin-top: 20px;
        text-align: center;
        padding: 10px;
        background-color: #003366;
        color: #ffffff;
    
     
    
    }


</style>  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>

<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Campus Recruitment</a>
            <div class="ml-auto">
                <?php if (isset($student_name)): ?>
                    <span class="text-white">Welcome, <?php echo htmlspecialchars($student_name); ?>!</span>
                <?php endif; ?>
                <a href="student_profile.php" class="btn btn-light">student profile</a>
                <a href=" stu_satus.php" class="btn btn-light">student status</a>
            
            
            </div>
        </div>
    </nav>
</header>

<div class="container mt-5">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>

    <div class="row mb-5">
        <div class="col-12">
            <h3>Job Vacancies</h3>
            <div class="card-columns">
                <?php while ($vacancy_row = $vacancy_result->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $vacancy_row['title']; ?></h5>
                            <p class="card-text"><?php echo $vacancy_row['description']; ?></p>
                            <p><strong>Company:</strong> <?php echo $vacancy_row['company']; ?></p>
                            <p><strong>Posted Date:</strong> <?php echo $vacancy_row['posted_date']; ?></p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#vacancyModal" data-id="<?php echo $vacancy_row['id']; ?>">Apply Now</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h3>Workshops</h3>
            <div class="card-columns">
                <?php while ($workshop_row = $workshop_result->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $workshop_row['title']; ?></h5>
                            <p class="card-text"><?php echo $workshop_row['description']; ?></p>
                            <p><strong>Date:</strong> <?php echo $workshop_row['date']; ?></p>
                            <p><strong>Location:</strong> <?php echo $workshop_row['location']; ?></p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#workshopModal" data-id="<?php echo $workshop_row['id']; ?>">Register Now</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vacancyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Job Application</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" id="job_id">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="resume">Resume</label>
                        <input type="file" class="form-control-file" id="resume" name="resume" required>
                    </div>
                    <div class="form-group">
                        <label for="cover_page">Cover Page (Optional)</label>
                        <textarea class="form-control" id="cover_page" name="cover_page"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="workshopModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Workshop Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <input type="hidden" name="workshop_id" id="workshop_id">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#vacancyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var jobId = button.data('id');
        $('#job_id').val(jobId);
    });

    $('#workshopModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var workshopId = button.data('id');
        $('#workshop_id').val(workshopId);
    });
</script>

</body>
</html>

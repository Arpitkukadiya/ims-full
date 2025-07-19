<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "campus";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the application data
$sql = "SELECT applications.id, applications.name, applications.email, applications.resume, applications.cover_page, vacancies.title AS vacancy_title, applications.status
        FROM applications 
        JOIN vacancies ON applications.vacancies_id = vacancies.id";
$applications_data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="student_dashboard.php" class="btn btn-light">Back to Dashboard</a>
                <a href="stu_satus.php" class="btn btn-light">student job status</a>
            </div>
        </div>
    </nav>
</header>
<div class="container my-4">
    <h1 class="text-center">Applications List</h1>
    <div class="row">
        <?php while ($row = $applications_data->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['vacancy_title']; ?></h5>
                        <p class="card-text"><strong>Name:</strong> <?php echo $row['name']; ?></p>
                        <p class="card-text"><strong>Email:</strong> <?php echo $row['email']; ?></p>

                        <!-- Status Section: Display as text -->
                        <p class="card-text"><strong>Status:</strong> 
                            <?php 
                                if ($row['status'] == 'pending') {
                                    echo '<span class="text-warning">Pending</span>';
                                } elseif ($row['status'] == 'approved') {
                                    echo '<span class="text-success">Approved</span>';
                                } elseif ($row['status'] == 'rejected') {
                                    echo '<span class="text-danger">Rejected</span>';
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>

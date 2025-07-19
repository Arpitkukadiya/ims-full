<?php
// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert Vacancy
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    print_r($_POST); 

    if (isset($_POST['addVacancy'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $company_id = $_POST['company_id'];

        // Check if company_id is set and is an integer
        if (isset($company_id) && is_numeric($company_id)) {
            // Fetch the company name using the company_id
            $companyQuery = $conn->prepare("SELECT name FROM users WHERE student_id = ?");
            $companyQuery->bind_param("i", $company_id);
            $companyQuery->execute();
            $companyQuery->bind_result($company_name);
            $companyQuery->fetch();
            $companyQuery->close();

            $posted_date = date('Y-m-d');

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO vacancies (company_id, title, description, company, posted_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $company_id, $title, $description, $company_name, $posted_date);

            if ($stmt->execute()) {
                echo "<script>alert('New vacancy added successfully.');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            $stmt->close();

            // Optionally redirect to avoid resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<script>alert('Invalid company ID.');</script>";
        }
    }
}

// Query to retrieve vacancies data with company names
$vacancy_data = $conn->query("
    SELECT v.id, v.title, v.description, v.posted_date, u.name AS company 
    FROM vacancies v 
    JOIN users u ON v.company_id = u.student_id
");

// Close the database connection at the end
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3>Vacancy Management</h3>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVacancyModal">Add Vacancy</button>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Company</th>
                <th>Posted Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $vacancy_data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['company']; ?></td>
                    <td><?php echo $row['posted_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Vacancy Modal -->
<div class="modal fade" id="addVacancyModal" tabindex="-1" aria-labelledby="addVacancyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVacancyModalLabel">Add Vacancy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <select class="form-control" name="company_id" >
                            <?php
                            // Re-establish the connection to fetch companies for the dropdown
                            $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
                            $companies = $conn->query("SELECT student_id, name FROM users WHERE role='company'");
                            while ($company = $companies->fetch_assoc()):
                            ?>
                                <option value="<?php echo $company['student_id']; ?>"><?php echo $company['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success" name="addVacancy">Add Vacancy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$logged_in_company_id = $_SESSION['company_id'];


if (!isset($_SESSION['company_name'])) {
    $company_name = 'Unknown Company';
} else {
    $company_name = $_SESSION['company_name'];
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addVacancy'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company_id = $_POST['company_id'];
    $company = $_POST['company'];
    $posted_date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO vacancies (company_id, title, description, company, posted_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $company_id, $title, $description, $company, $posted_date);

    if ($stmt->execute()) {
        echo "New vacancy added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_vacancy'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company_id = $_POST['company_id'];
    $company = $_POST['company'];

    $stmt = $conn->prepare("UPDATE vacancies SET title = ?, description = ?, company_id = ?, company = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $description, $company_id, $company, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: c_vacancies.php");
    exit();
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM vacancies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: c_vacancies.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM vacancies WHERE company_id = ?");
$stmt->bind_param("i", $logged_in_company_id);
$stmt->execute();
$vacancy_data = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacancy Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>/
    </style>
</head>
<body>
<?php include "comp_nav.php"; ?>

    <div class="content flex-grow-1 p-4">
        <h3 class="mb-4">Vacancy Management</h3>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addVacancyModal">Add Vacancy</button>

        <div class="modal fade" id="addVacancyModal" tabindex="-1" aria-labelledby="addVacancyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVacancyLabel">Add Vacancy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control" id="description" required></textarea>
                            </div>
                            <input type="hidden" name="company_id" value="<?php echo $logged_in_company_id; ?>">
                            <input type="hidden" name="company" value="<?php echo htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8'); ?>"> <!-- Add company name -->
                            <button type="submit" name="addVacancy" class="btn btn-primary">Add Vacancy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
  <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                  
                    <th>Posted Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $vacancy_data->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        
                        <td><?php echo htmlspecialchars($row['posted_date']); ?></td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editVacancyModal" 
                               data-id="<?php echo htmlspecialchars($row['id']); ?>" 
                               data-title="<?php echo htmlspecialchars($row['title']); ?>" 
                               data-description="<?php echo htmlspecialchars($row['description']); ?>" 
                               data-company="<?php echo htmlspecialchars($row['company']); ?>"
                               data-company-id="<?php echo htmlspecialchars($row['company_id']); ?>">Edit</a>
                            <a href="?delete=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editVacancyModal" tabindex="-1" aria-labelledby="editVacancyLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVacancyLabel">Edit Vacancy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="mb-3">
                            <label for="edit-title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="edit-title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="edit-description" required></textarea>
                        </div>
                        <input type="hidden" id="edit-company_id" name="company_id"> 
                        <input type="hidden" id="edit-company" name="company"> 
                        <button type="submit" name="edit_vacancy" class="btn btn-primary">Update Vacancy</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
      
        const editVacancyModal = document.getElementById('editVacancyModal');
        editVacancyModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');
            const description = button.getAttribute('data-description');
            const company = button.getAttribute('data-company');
            const company_id = button.getAttribute('data-company-id');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-company').value = company;
            document.getElementById('edit-company_id').value = company_id;
        });
    </script>
</body>
</html>

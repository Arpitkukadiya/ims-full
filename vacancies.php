<?php
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addVacancy'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company_id = $_POST['company_id']; 
    $company = $_POST['company']; 
    $posted_date = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare("INSERT INTO vacancies (title, description, company_id, company, posted_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $title, $description, $company_id, $company, $posted_date);
    $stmt->execute();
    $stmt->close();
    header("Location: vacancies.php");
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_vacancy'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company = $_POST['company'];

    $stmt = $conn->prepare("UPDATE vacancies SET title = ?, description = ?, company = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $company, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: vacancies.php");
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM vacancies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: vacancies.php");
}

$vacancy_data = $conn->query("SELECT * FROM vacancies");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacancy Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css"> 
    <style>
    .table {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        background-color: #fff;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center; 
    }

    .table th {
        background-color: #000; 
        color: #ffffff;
        font-weight: bold;
    }

    .table td {
        color: #333; 
    }

    .btn {
        border-radius: 4px;
    }

    .modal-content {
        background-color: #f8f9fa; 
    }

    .modal-header, .modal-footer {
        background-color: #000; 
        color: #ffffff; 
    }

    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            height: auto;
            width: 100%;
        }

        .content {
            margin-left: 0;
        }
    }
    </style>
</head>
<body>
 <?php 
  include "admin_nevbar.php";
  ?>
    <div class="content flex-grow-1 p-4 mt-5 pt-5">
        <h3 class="mb-4">Vacancy Management</h3>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addVacancyModal">Add Vacancy</button>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Posted Date</th>
                    <th>Actions</th>
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
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editVacancyModal" 
                               data-id="<?php echo $row['id']; ?>" 
                               data-title="<?php echo $row['title']; ?>" 
                               data-description="<?php echo $row['description']; ?>" 
                               data-company="<?php echo $row['company']; ?>">Edit</a>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

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
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company ID</label>
                        <input type="number" class="form-control" id="company_id" name="company_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                    <button type="submit" name="addVacancy" class="btn btn-primary">Add Vacancy</button>
                </form>
            </div>
        </div>
    </div>
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
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="edit_company" name="company" required>
                    </div>
                    <button type="submit" name="edit_vacancy" class="btn btn-primary">Update Vacancy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
   
    var editVacancyModal = document.getElementById('editVacancyModal');
    editVacancyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var description = button.getAttribute('data-description');
        var company = button.getAttribute('data-company');

        var modalTitle = editVacancyModal.querySelector('.modal-title');
        var editIdInput = editVacancyModal.querySelector('#edit_id');
        var editTitleInput = editVacancyModal.querySelector('#edit_title');
        var editDescriptionInput = editVacancyModal.querySelector('#edit_description');
        var editCompanyInput = editVacancyModal.querySelector('#edit_company');

        modalTitle.textContent = 'Edit Vacancy';
        editIdInput.value = id;
        editTitleInput.value = title;
        editDescriptionInput.value = description;
        editCompanyInput.value = company;
    });
</script>
</body>
</html>

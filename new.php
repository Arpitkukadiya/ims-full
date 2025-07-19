
<?php

$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateVacancy'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $company_id = $_POST['company_id']; 
    $company = $_POST['company']; 

    $stmt = $conn->prepare("UPDATE vacancies SET company_id = ?, title = ?, description = ?, company = ? WHERE id = ?");
    $stmt->bind_param("isssi", $company_id, $title, $description, $company, $id);

    if ($stmt->execute()) {
        echo "Vacancy updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addEvent'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    
    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $event_date, $location);

    if ($stmt->execute()) {
        echo "New event added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM vacancies WHERE id=$id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$vacancy_data = $conn->query("SELECT * FROM vacancies");
$event_data = $conn->query("SELECT * FROM events");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f8; font-family: Arial, sans-serif; }
        .sidebar { background-color: #343a40; padding: 15px; height: 100vh; }
        .sidebar h4 { color: #ffffff; }
        .sidebar a { color: #ffffff; text-decoration: none; display: block; margin: 10px 0; }
        .table-container { margin: 20px; padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); }
        .dashboard-header { background-color: #007bff; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar">
        <h4>Admin Dashboard</h4>
        <a href="#" onclick="showSection('vacancy-management')">Vacancy Management</a>
        <a href="#" onclick="showSection('event-management')">Event Management</a>
    </div>

    <div class="flex-grow-1">
        <div class="dashboard-header">
            <h1>Admin</h1>
        </div>

        <div id="vacancy-management" class="table-container">
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

        <div id="event-management" class="table-container" style="display: none;">
            <h3>Event Management</h3>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventModal">Add Event</button>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Event Date</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $event_data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['event_date']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
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
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit-description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company ID</label>
                        <input type="number" class="form-control" id="edit-company_id" name="company_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="edit-company" name="company" required>
                    </div>
                    <button type="submit" name="updateVacancy" class="btn btn-primary">Update Vacancy</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEventLabel">Add Event</h5>
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
                        <label for="event_date" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="event_date" name="event_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <button type="submit" name="addEvent" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('editVacancyModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var title = button.getAttribute('data-title');
        var description = button.getAttribute('data-description');
        var company = button.getAttribute('data-company');
        
        var modalIdInput = editModal.querySelector('#id');
        var modalTitleInput = editModal.querySelector('#edit-title');
        var modalDescriptionInput = editModal.querySelector('#edit-description');
        var modalCompanyInput = editModal.querySelector('#edit-company');
        
        modalIdInput.value = id;
        modalTitleInput.value = title;
        modalDescriptionInput.value = description;
        modalCompanyInput.value = company;
    });
});

function showSection(sectionId) {
    document.getElementById('vacancy-management').style.display = 'none';
    document.getElementById('event-management').style.display = 'none';
    document.getElementById(sectionId).style.display = 'block';
}
</script>
</body>
</html>

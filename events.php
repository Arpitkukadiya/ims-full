<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {

        $title = $_POST['title'];
        $description = $_POST['description'];
        $event_date = $_POST['event_date'];
        $location = $_POST['location'];

        if (empty($title) || empty($description) || empty($event_date) || empty($location)) {
            echo "All fields are required.";
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO events (title, description, date, location) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            echo "Error preparing statement: " . htmlspecialchars($conn->error);
            exit();
        }

        $stmt->bind_param("ssss", $title, $description, $event_date, $location);

        if ($stmt->execute()) {
            header("Location: events.php");
            exit();
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
      
        $event_id = $_POST['event_id'];
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        if ($stmt === false) {
            echo "Error preparing statement: " . htmlspecialchars($conn->error);
            exit();
        }

        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            header("Location: events.php");
            exit();
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    }
}

$event_data = $conn->query("SELECT * FROM events");
if ($event_data === false) {
    echo "Error retrieving events: " . htmlspecialchars($conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    
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
<body><body>
<?php include "admin_nevbar.php"; ?>
    <div class="content">
        <h3>Event Management</h3>

        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">Add Event</button>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $event_data->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>
                            <form method="POST" action="events.php" style="display:inline-block;">
                                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventLabel">Add Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="events.php">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="eventTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="eventDate" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="eventLocation" name="location" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
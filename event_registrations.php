<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'campus';

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT 
            event_registered.id, 
            events.title AS event_name, 
            event_registered.name, 
            event_registered.email, 
            event_registered.mobile, 
            events.date AS registration_date
        FROM 
            event_registered
        JOIN 
            events 
        ON 
            event_registered.event_id = events.id";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>

  .table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        tr {
            background-color: black;
            color: white;
            text-align: center;
        }

        .table td {
            text-align: center;
            color: #333;
        }

        .btn {
            border-radius: 4px;
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    </style>
</head>
<body>
<?php 
  include "admin_nevbar.php";
  ?>
      <div class="content flex-grow-1 p-4">

    <div class="container my-4 mt-5 pt-5">
        <h1>List of Registered Events</h1>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event Name</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['event_name']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['mobile']; ?></td>
                            <td><?php echo $row['registration_date']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No registrations found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>

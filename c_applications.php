<?php

session_start();

include 'config.php';

$company_id = isset($_SESSION['company_id']) ? $_SESSION['company_id'] : 0;

if ($company_id == 0) {
    echo "You need to log in to view this page.";
    exit;
}

$sql = "
    SELECT 
        applications.id, applications.name, applications.email, applications.resume, applications.cover_page, 
        vacancies.title AS vacancy_title, applications.status
    FROM 
        applications 
    JOIN 
        vacancies 
    ON 
        applications.vacancies_id = vacancies.id
    WHERE 
        vacancies.company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$applications_data = $stmt->get_result();

if (isset($_POST['update_status']) && isset($_POST['application_id']) && isset($_POST['status'])) {
    $application_id = $_POST['application_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE applications SET status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $application_id);

    if ($update_stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Status updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating status."]);
    }
    $update_stmt->close();
    exit;
}


if (isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];

    $sql = "
        SELECT 
            applications.name, 
            applications.email, 
            applications.cover_page, 
            vacancies.title AS vacancy_title
        FROM 
            applications
        JOIN 
            vacancies 
        ON 
            applications.vacancies_id = vacancies.id
        WHERE 
            applications.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "No application found."]);
    }

    $stmt->close();
} else {
   // echo json_encode(["error" => "Invalid request."]);
}



?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
          </head>
    <body>
        <?php include "comp_nav.php"; ?>

        <div class="content">
            <h3>Application Management</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vacancy</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Resume</th>
                        <th>Cover Letter</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $applications_data->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['vacancy_title']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <?php 
                                    $resume_path =  $row['resume'];
                                    if (!empty($row['resume']) && file_exists($resume_path)): 
                                ?>
                                    <a class="btn btn-primary" href="<?php echo $resume_path; ?>" target="_blank">View Resume</a>
                                <?php elseif (empty($row['resume'])): ?>
                                    <span class="text-warning">No resume uploaded</span>
                                <?php else: ?>
                                    <span class="text-danger">Resume not available</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['cover_page']; ?></td>
                            <td>
                                <select class="form-select status-dropdown" data-id="<?php echo $row['id']; ?>">
                                    <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?php echo $row['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $row['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </td>
                            <td>
                                
                            <!-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#applicationModal" data-id="1">
    View Details
</button> -->

                                <a href="c_applications.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this application?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="applicationModal" tabindex="-1" aria-labelledby="applicationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="applicationModalLabel">Application Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> <span id="applicant_name"></span></p>
                        <p><strong>Email:</strong> <span id="applicant_email"></span></p>
                        <p><strong>Vacancy:</strong> <span id="vacancy_title"></span></p>
                        <p><strong>Cover Letter:</strong> <span id="cover_page"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
         
            $(document).on('change', '.status-dropdown', function () {
                var application_id = $(this).data('id');
                var new_status = $(this).val();

                $.post('c_applications.php', { update_status: true, application_id: application_id, status: new_status }, function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                });
            });

           
$('#applicationModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var application_id = button.data('id'); 

  
    $('#applicant_name').text('Loading...');
    $('#applicant_email').text('');
    $('#vacancy_title').text('');
    $('#cover_page').text('');

   
    $.post('fetch_application_details.php', { application_id: application_id }, function (response) {
        var data = JSON.parse(response);

        if (data.error) {
            alert(data.error);
        } else {
            $('#applicant_name').text(data.name);
            $('#applicant_email').text(data.email);
            $('#vacancy_title').text(data.vacancy_title);
            $('#cover_page').text(data.cover_page);
        }
    });
});

        </script>
    </body>
    </html>

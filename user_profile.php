<?php
include 'config.php'; 

$query = "SELECT * FROM profile"; 
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $profiles = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $profiles = [];
}
$query_users = "SELECT name,email, role FROM users"; 

$result_users = $conn->query($query_users);

if ($result_users->num_rows > 0) {
    $users = $result_users->fetch_all(MYSQLI_ASSOC); 
} else {
    $users = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $profile_id = $_POST['profile_id'];
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $certificate = $_POST['certificate'];

    
    $query = "UPDATE profile SET Name = ?, Bio = ?, Experience = ?, Skills = ?, Certificate = ? WHERE Profile_id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssssi", $name, $bio, $experience, $skills, $certificate, $profile_id);

        if ($stmt->execute()) {
           
            header("Location: user_profile.php"); 
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

      
        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
 
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobseeker Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css"> 
    <style>
        body {
            padding-top: 80px; 
        }

        .container {
            padding-left: 200px;
            padding-right: 15px;
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
            padding: 8px;
        }

    </style>
</head>
<body>
<?php 
  include "admin_nevbar.php"; 
?>

<div class="container mt-5">
    <h2>Student Profiles</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Profile Image</th>
                <th>Bio</th>
                <th>Experience</th>
                <th>Skills</th>
                <th>Certificates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($profiles) > 0): ?>
                <?php foreach ($profiles as $index => $profile): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($profile['Name']); ?></td>
                        <td>
                            <?php if (!empty($profile['Profile_image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($profile['Profile_image']); ?>" alt="Profile Image" class="img-fluid" style="max-width: 100px;">
                            <?php else: ?>
                                <p>No Image</p>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($profile['Bio']); ?></td>
                        <td><?php echo htmlspecialchars($profile['Experience']); ?></td>
                        <td><?php echo htmlspecialchars($profile['Skills']); ?></td>
                        <td><?php echo htmlspecialchars($profile['Certificate']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal" 
                                data-id="<?php echo $profile['Profile_id']; ?>" 
                                data-name="<?php echo htmlspecialchars($profile['Name']); ?>" 
                                data-bio="<?php echo htmlspecialchars($profile['Bio']); ?>" 
                                data-experience="<?php echo htmlspecialchars($profile['Experience']); ?>" 
                                data-skills="<?php echo htmlspecialchars($profile['Skills']); ?>" 
                                data-certificate="<?php echo htmlspecialchars($profile['Certificate']); ?>"
                            >Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No profiles found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Users</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
               
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                       <!--  <td>
                            Add actions for user management (edit, delete, etc.) 
                            <button class="btn btn-warning btn-sm">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>-->
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="user_profile.php" method="POST">
          <input type="hidden" name="profile_id" id="profile_id">

          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>

          <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio" required></textarea>
          </div>

          <div class="mb-3">
            <label for="experience" class="form-label">Experience</label>
            <textarea class="form-control" id="experience" name="experience" required></textarea>
          </div>

          <div class="mb-3">
            <label for="skills" class="form-label">Skills</label>
            <input type="text" class="form-control" id="skills" name="skills" required>
          </div>

          <div class="mb-3">
            <label for="certificate" class="form-label">Certificates</label>
            <input type="text" class="form-control" id="certificate" name="certificate" required>
          </div>

          <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.querySelectorAll('.btn-warning').forEach(function(button) {
        button.addEventListener('click', function() {
            var profileId = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var bio = button.getAttribute('data-bio');
            var experience = button.getAttribute('data-experience');
            var skills = button.getAttribute('data-skills');
            var certificate = button.getAttribute('data-certificate');
            
            document.getElementById('profile_id').value = profileId;
            document.getElementById('name').value = name;
            document.getElementById('bio').value = bio;
            document.getElementById('experience').value = experience;
            document.getElementById('skills').value = skills;
            document.getElementById('certificate').value = certificate;
        });
    });
</script>

</body>
</html>

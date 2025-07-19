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

    $profile_sql = "SELECT Profile_id, Jobseeker_id, Name, Profile_image, Bio, Experience, Skills, Certificate 
                    FROM profile WHERE Jobseeker_id = ?";

    $stmt = $conn->prepare($profile_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($profile_id, $jobseeker_id, $name, $profile_image, $bio, $experience, $skills, $certificate);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profile_id = $_POST['profile_id'];
    $jobseeker_id = $_POST['jobseeker_id'];
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $certificate = $_POST['certificate'];

    $new_profile_image = $profile_image; 

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
       
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
           
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $new_profile_image = basename($_FILES["profile_image"]["name"]);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

   
    $update_sql = "UPDATE profile SET Name = ?, Profile_image = ?, Bio = ?, Experience = ?, Skills = ?, Certificate = ? WHERE Profile_id = ? AND Jobseeker_id = ?";
    $stmt = $conn->prepare($update_sql);

    $stmt->bind_param("ssssssii", $name, $new_profile_image, $bio, $experience, $skills, $certificate, $profile_id, $jobseeker_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        header("Location: student_dashboard.php"); 
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
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

        .navbar {
            background-color: #003366; 
        }

        .navbar-brand {
            color: #ffffff !important;
        }

        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .modal-header {
            background-color: #003366;
            color: #ffffff;
        }

        .modal-body {
            background-color: #ffffff;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #003366;
            color: #ffffff;
        }
    </style>
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
            </div>
        </div>
    </nav>
</header>

<div class="container mt-5">
    <h2>Jobseeker Profile</h2>

    <div id="profile_view">
        <div class="form-group">
            <label for="name">Name</label>
            <p id="name"><?php echo htmlspecialchars($name); ?></p>
        </div>

        <div class="form-group">
            <label for="profile_image">Profile Image</label>
            <div>
                <?php if ($profile_image): ?>
                    <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="img-fluid" style="max-width: 100px;">
                <?php else: ?>
                    <p>No Image</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <p id="bio"><?php echo htmlspecialchars($bio); ?></p>
        </div>

        <div class="form-group">
            <label for="experience">Experience</label>
            <p id="experience"><?php echo htmlspecialchars($experience); ?></p>
        </div>

        <div class="form-group">
            <label for="skills">Skills</label>
            <p id="skills"><?php echo htmlspecialchars($skills); ?></p>
        </div>

        <div class="form-group">
            <label for="certificate">Certificates</label>
            <p id="certificate"><?php echo htmlspecialchars($certificate); ?></p>
        </div>

        <button class="btn btn-primary" id="edit_button">Edit</button>
    </div>

    <div id="profile_edit" style="display:none;">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_id">Profile ID</label>
                <input type="text" class="form-control" id="profile_id" name="profile_id" value="<?php echo htmlspecialchars($profile_id); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="jobseeker_id">Jobseeker ID</label>
                <input type="text" class="form-control" id="jobseeker_id" name="jobseeker_id" value="<?php echo htmlspecialchars($jobseeker_id); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label for="profile_image">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image">
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3" required><?php echo htmlspecialchars($bio); ?></textarea>
            </div>

            <div class="form-group">
                <label for="experience">Experience</label>
                <textarea class="form-control" id="experience" name="experience" rows="3" required><?php echo htmlspecialchars($experience); ?></textarea>
            </div>

            <div class="form-group">
                <label for="skills">Skills</label>
                <textarea class="form-control" id="skills" name="skills" rows="3" required><?php echo htmlspecialchars($skills); ?></textarea>
            </div>

            <div class="form-group">
                <label for="certificate">Certificates</label>
                <textarea class="form-control" id="certificate" name="certificate" rows="3" required><?php echo htmlspecialchars($certificate); ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" id="cancel_button">Cancel</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Arpit Kukadiya. All Rights Reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.getElementById('edit_button').addEventListener('click', function() {
        document.getElementById('profile_view').style.display = 'none';
        document.getElementById('profile_edit').style.display = 'block';
    });

    document.getElementById('cancel_button').addEventListener('click', function() {
        document.getElementById('profile_edit').style.display = 'none';
        document.getElementById('profile_view').style.display = 'block';
    });
</script>

</body>
</html>

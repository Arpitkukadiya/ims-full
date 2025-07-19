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

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student name
$student_sql = "SELECT name FROM users WHERE student_id = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

// Check if the profile exists, if not insert default data
$check_sql = "SELECT COUNT(*) FROM profile WHERE Jobseeker_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    $insert_sql = "INSERT INTO profile (Jobseeker_id, Name, Bio, Experience, Skills, Certificate, Profile_image) VALUES (?, '', '', '', '', '', '')";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch profile data
$profile_sql = "SELECT Profile_id, Jobseeker_id, Name, Profile_image, Bio, Experience, Skills, Certificate FROM profile WHERE Jobseeker_id = ?";
$stmt = $conn->prepare($profile_sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($profile_id, $jobseeker_id, $name, $profile_image, $bio, $experience, $skills, $certificate);
$stmt->fetch();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $skills = $_POST['skills'];
    $certificate = $_POST['certificate'];
    $new_profile_image = $profile_image;

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type and size
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($_FILES['profile_image']['size'] > 500000) {
            echo "Error: File size should not exceed 500 KB.";
        } else {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $new_profile_image = basename($_FILES["profile_image"]["name"]);
            } else {
                echo "Error uploading the file.";
            }
        }
    }

    // Update profile data
    $update_sql = "UPDATE profile SET Name = ?, Profile_image = ?, Bio = ?, Experience = ?, Skills = ?, Certificate = ? WHERE Profile_id = ? AND Jobseeker_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssii", $name, $new_profile_image, $bio, $experience, $skills, $certificate, $profile_id, $jobseeker_id);

    if ($stmt->execute()) {
        header("Location: student_dashboard.php");
        exit;
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
    <title>Jobseeker Profile</title>
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
        footer {
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #003366;
            color: #ffffff;
        }
        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Campus Recruitment</a>
            <div class="ml-auto">
                <span class="text-white">Welcome, <?php echo htmlspecialchars($student_name); ?>!</span>
                <a href="student_dashboard.php" class="btn btn-light">Back to Dashboard</a>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-5">
    <h2>Jobseeker Profile</h2>

    <div id="profile_view">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name ?: 'No Name Provided'); ?></p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($bio ?: 'No Bio Provided'); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($experience ?: 'No Experience Provided'); ?></p>
        <p><strong>Skills:</strong> <?php echo htmlspecialchars($skills ?: 'No Skills Provided'); ?></p>
        <p><strong>Certificates:</strong> <?php echo htmlspecialchars($certificate ?: 'No Certificates Provided'); ?></p>
        <p>
            <strong>Profile Image:</strong>
            <?php if ($profile_image): ?>
                <img src="uploads/<?php echo htmlspecialchars($profile_image); ?>" style="max-width: 100px;">
            <?php else: ?>
                No Image
            <?php endif; ?>
        </p>
        <button class="btn btn-primary" id="edit_button">Edit</button>
    </div>

    <div id="profile_edit" style="display: none;">
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="profile_id" value="<?php echo htmlspecialchars($profile_id); ?>">
            <input type="hidden" name="jobseeker_id" value="<?php echo htmlspecialchars($jobseeker_id); ?>">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" class="form-control" rows="3"><?php echo htmlspecialchars($bio); ?></textarea>
            </div>
            <div class="form-group">
                <label>Experience</label>
                <textarea name="experience" class="form-control" rows="3"><?php echo htmlspecialchars($experience); ?></textarea>
            </div>
            <div class="form-group">
                <label>Skills</label>
                <textarea name="skills" class="form-control" rows="3"><?php echo htmlspecialchars($skills); ?></textarea>
            </div>
            <div class="form-group">
                <label>Certificates</label>
                <textarea name="certificate" class="form-control" rows="3"><?php echo htmlspecialchars($certificate); ?></textarea>
            </div>
            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" id="cancel_button">Cancel</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Arpit Kukadiya. All Rights Reserved.</p>
</footer>

<script>
    document.getElementById('edit_button').addEventListener('click', function() {
        document.getElementById('profile_view').style.display = 'none';
        document.getElementById('profile_edit').style.display = 'block';
    });
    document.getElementById('cancel_button').addEventListener('click', function() {
        document.getElementById('profile_view').style.display = 'block';
        document.getElementById('profile_edit').style.display = 'none';
    });
</script>
</body>
</html>

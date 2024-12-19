<?php
session_start();
include('../includes/db.php');

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student data
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $major = $_POST['major'];
    $address = $_POST['address'];

    // Handle profile photo upload
    $profile_photo_data = $student['profile_photo']; // Retain the existing photo if no new one is uploaded
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $profile_photo_data = file_get_contents($_FILES['profile_photo']['tmp_name']);
    }

    // Update student data
    $update_sql = "UPDATE students SET name = ?, email = ?, phone_number = ?, major = ?, address = ?, profile_photo = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $name, $email, $phone, $major, $address, $profile_photo_data, $student_id);

    if ($update_stmt->execute()) {
        header('Location: student_profile.php');
        exit();
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}

// Decode and display the profile photo if it exists
$profile_photo_url = $student['profile_photo']
    ? 'data:image/jpeg;base64,' . base64_encode($student['profile_photo'])
    : "default-profile-photo.jpg";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            text-align: center;
        }

        .profile-card {
            background-color: #fff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 600px;
            margin: 0 auto;
        }

        .profile-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 4px solid #2980b9;
            margin-bottom: 20px;
            object-fit: cover;
        }

        h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .profile-info {
            margin-bottom: 20px;
            font-size: 18px;
            color: #7f8c8d;
            text-align: left;
        }

        .profile-info label {
            display: block;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .profile-info input,
        .profile-info textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .profile-info input[type="file"] {
            padding: 5px;
        }

        .save-button {
            background-color: #2980b9;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .save-button:hover {
            background-color: #3498db;
        }

    
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .profile-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Profile Card -->
        <div class="profile-card">
            <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                <img src="<?php echo $profile_photo_url; ?>" alt="Profile Photo" class="profile-photo">

                <h2>Edit Profile</h2>

                <div class="profile-info">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>

                    <label for="major">Major:</label>
                    <input type="text" id="major" name="major" value="<?php echo htmlspecialchars($student['major']); ?>" required>

                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>

                    <label for="profile_photo">Profile Photo:</label>
                    <input type="file" id="profile_photo" name="profile_photo">
                </div>

                <button type="submit" class="save-button">Save Changes</button>
            </form>
        </div>
    </div>
</body>

</html>
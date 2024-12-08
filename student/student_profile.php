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

// Prepare profile photo as a data URL if exists
$profile_photo_url = $student['profile_photo']
    ? 'data:image/jpeg;base64,' . base64_encode($student['profile_photo'])
    : 'default-profile-photo.jpg'; // Provide a default photo if none exists

// Handle image download request
if (isset($_GET['download']) && $student['profile_photo']) {
    header('Content-Disposition: attachment; filename="profile_photo.jpg"');
    header('Content-Type: image/jpeg');
    echo $student['profile_photo'];
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>tudent Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-size: 18px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .navbar a:hover {
            background-color: #34495e;
            border-radius: 5px;
            transform: scale(1.05);
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

        .profile-info span {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        button {
            background-color: #2980b9;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
            transition: background-color 0.3s;
        }

        button:hover {
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
    <!-- Navigation Bar -->
    <div class="navbar">
        <div><strong>Student Dashboard</strong></div>
        <div>
            <a href="student_dashboard.php">Home</a>
            <a href="contact_admin.php">Admin's Contact</a>
            <a href="student_profile.php">Student Profile</a>
            <a href="student_logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="profile-card">
            <img src="<?php echo $profile_photo_url; ?>" alt="Profile Photo" class="profile-photo">
            <h2><?php echo htmlspecialchars($student['name']); ?>'s Profile</h2>
            <div class="profile-info">
                <span>Email: <?php echo htmlspecialchars($student['email']); ?></span>
                <span>Phone: <?php echo htmlspecialchars($student['phone_number']); ?></span>
                <span>Address: <?php echo htmlspecialchars($student['address']); ?></span>
                <span>Major: <?php echo htmlspecialchars($student['major']); ?></span>
            </div>
            <button onclick="window.location.href='edit_profile.php';">Edit Profile</button>
            <?php if ($student['profile_photo']): ?>
                <button onclick="window.location.href='student_profile.php?download=true';">Download Photo</button>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
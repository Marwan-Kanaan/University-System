<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit;
}

include('../../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $course_description = $_POST['course_description'];

    $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, course_description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $course_name, $course_code, $course_description);

    if ($stmt->execute()) {
        header('Location: manage_courses.php');
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            width: 50%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            margin-left: 30px;
            margin-right: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .profile-card {
                width: 100%;
            }
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Course</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" id="course_name" name="course_name" required>
            </div>
            <div class="form-group">
                <label for="course_code">Course code</label>
                <input type="text" id="course_code" name="course_code" required>
            </div>
            <div class="form-group">
                <label for="course_description">Course Description</label>
                <input type="text" id="course_description" name="course_description" required>
            </div>
            <button type="submit">Add Course</button>
        </form>
    </div>
</body>

</html>
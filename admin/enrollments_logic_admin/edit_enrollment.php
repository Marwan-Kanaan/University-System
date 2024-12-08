<?php
include "../../includes/db.php";

// Check if an ID is provided in the URL for editing
if (isset($_GET['id'])) {
    $enrollment_id = $_GET['id'];
    // Fetch the current enrollment data
    $result = $conn->query("SELECT * FROM enrollments WHERE id = $enrollment_id");
    $enrollment = $result->fetch_assoc();
}

// Get all students and courses for the drop-downs
$students = $conn->query("SELECT id, name FROM students");
$courses = $conn->query("SELECT id, course_name FROM courses");

// Handle form submission (update enrollment)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];

    // Update query to modify the enrollment
    $query = "UPDATE enrollments SET course_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $course_id, $enrollment_id);

    if ($stmt->execute()) {
        // Redirect to manage_enrollment.php with a success message
        header("Location: manage_enrollments.php?message=Enrollment updated successfully");
        exit();
    } else {
        echo "Error updating enrollment: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .profile-card {
                width: 100%;
            }
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
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        input[type="text"] {
            width: 100%;
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
        <h1>Edit Enrollment</h1>
        <form action="edit_enrollment.php?id=<?php echo $enrollment_id; ?>" method="POST">
            <div class="form-group">
                <label for="student">Student</label>
                <select name="student_id" id="student" disabled>
                    <option value="<?php echo $enrollment['student_id']; ?>">
                        <?php
                        // Get the name of the selected student
                        $student_query = $conn->query("SELECT name FROM students WHERE id = " . $enrollment['student_id']);
                        $student_data = $student_query->fetch_assoc();
                        echo $student_data['name'];
                        ?>
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="course">Select Course</label>
                <select name="course_id" id="course" required>
                    <?php while ($course = $courses->fetch_assoc()) { ?>
                        <option value="<?php echo $course['id']; ?>" <?php if ($course['id'] == $enrollment['course_id']) echo 'selected'; ?>>
                            <?php echo $course['course_name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit">Update Enrollment</button>
        </form>
    </div>

</body>

</html>
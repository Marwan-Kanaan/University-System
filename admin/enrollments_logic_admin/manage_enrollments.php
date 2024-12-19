<?php
// Start session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit;
}

include "../../includes/db.php";

// Handle search functionality
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $search_term = "%$search_query%";
    $query = "
        SELECT enrollments.id, students.name AS student_name, courses.course_name, enrollments.enrollment_date
        FROM enrollments
        JOIN students ON enrollments.student_id = students.id
        JOIN courses ON enrollments.course_id = courses.id
        WHERE courses.course_name LIKE ? OR students.name LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search_term, $search_term);
} else {
    // Default: Fetch all enrollments
    $query = "
        SELECT enrollments.id, students.name AS student_name, courses.course_name, enrollments.enrollment_date
        FROM enrollments
        JOIN students ON enrollments.student_id = students.id
        JOIN courses ON enrollments.course_id = courses.id";
    $stmt = $conn->prepare($query);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM enrollments WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_enrollments.php");
        exit;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 200px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-form button {
            padding: 8px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-btns a {
            text-decoration: none;
            padding: 5px 10px;
            color: white;
            background-color: #2c3e50;
            border-radius: 5px;
        }

        .action-btns a:hover {
            background-color: red;
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
        <h1>Manage Enrollments</h1>
        <div class="btn-container">
            <a href="../admin_dashboard.php"><button>Back to Dashboard</button></a>
            <a href="add_enrollment.php"><button>Add Enrollment</button></a>
        </div>

        <!-- Search Bar -->
        <div class="search-form">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by Course Name or Student Name" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Course Name</th>
                    <th>Enrollment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= $row['enrollment_date'] ?></td>
                        <td class="action-btns">
                            <a href="edit_enrollment.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['id']; ?>)">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <script>
                    function confirmDelete(enrollmentId) {
                        if (confirm("Are you sure you want to delete this enrollment?")) {
                            window.location.href = "delete_enrollment.php?id=" + enrollmentId;
                        }
                    }
                </script>


            </tbody>
        </table>
    </div>
</body>

</html>
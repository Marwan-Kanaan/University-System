<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Include the database connection
include('../../includes/db.php');

// Handle search functionality
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id LIKE ? OR name LIKE ? OR email LIKE ?");
    $search_term = "%$search_query%";
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
} else {
    // Default: Fetch all students
    $stmt = $conn->prepare("SELECT * FROM students");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
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

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .profile-card {
                width: 100%;
            }
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
    </style>
</head>

<body>

    <div class="container">
        <div class="btn-container">
            <!-- Button to go back to the dashboard -->
            <a href="../admin_dashboard.php"><button>Back to Dashboard</button></a>
            <!-- Button to add a new student -->
            <a href="add_student.php"><button>Add Student</button></a>
        </div>

        <h1>Manage Students</h1>

        <!-- Search Bar -->
        <div class="search-form">
            <form method="GET">
                <input type="text" name="search" placeholder="Search by ID, Name, or Email" value="<?php echo $search_query; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Major</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td><?php echo $student['major']; ?></td>
                        <td><?php echo $student['address']; ?></td>
                        <td><?php echo $student['phone_number']; ?></td>
                        <td class="action-btns">
                            <a href="edit_student.php?id=<?php echo $student['id']; ?>">Edit</a>
                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $student['id']; ?>)">Delete</a>
                        </td>
                    </tr>
                <?php } ?>

                <script>
                    function confirmDelete(studentId) {
                        if (confirm("Are you sure you want to delete this student?")) {
                            // Redirect to the delete page with the student ID
                            window.location.href = "delete_student.php?id=" + studentId;
                        }
                    }
                </script>

            </tbody>
        </table>
    </div>

</body>

</html>
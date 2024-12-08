<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in (admin or student)
if (!isset($_SESSION['student_id']) && !isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// If logged in as admin, fetch admin details
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Fetch admin data
    $admin_sql = "SELECT * FROM admins WHERE id = ?";
    $admin_stmt = $conn->prepare($admin_sql);
    $admin_stmt->bind_param("i", $admin_id);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();
    $admin = $admin_result->fetch_assoc();
}

// Fetch all admins for the contact page
$admin_query = "SELECT * FROM admins";
$admin_query_result = $conn->query($admin_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Contact Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styles */
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
            margin: 30px auto;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-top: 40px;
            font-weight: normal;
        }

        .admin-cards {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-items: center;
        }

        .admin-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 250px;
        }

        .admin-card h3 {
            margin: 10px 0;
            color: #2c3e50;
        }

        .admin-card p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .admin-card a {
            color: #2980b9;
            text-decoration: none;
        }

        .admin-card a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar a {
                margin: 5px 0;
                padding: 8px;
            }

            .container {
                width: 90%;
            }

            .admin-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div><strong>Admin Contact Page</strong></div>
        <div>
            <a href="student_dashboard.php">Home</a>
            <a href="contact_admin.php">Admin's Contact</a>
            <a href="student_profile.php">Student Profile</a>
            <a href="student_logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Contact an Admin</h2>

        <!-- Admin Cards -->
        <div class="admin-cards">
            <?php while ($admin = $admin_query_result->fetch_assoc()): ?>
                <div class="admin-card">
                    <h3><?php echo htmlspecialchars($admin['name']); ?></h3>
                    <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($admin['email']); ?>"><?php echo htmlspecialchars($admin['email']); ?></a></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone_number']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
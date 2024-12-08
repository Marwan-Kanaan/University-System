<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

include('../includes/db.php');



$total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$total_courses = $conn->query("SELECT COUNT(*) as total FROM courses")->fetch_assoc()['total'];
$total_enrollments = $conn->query("SELECT COUNT(*) as total FROM enrollments")->fetch_assoc()['total'];

// Fetch course enrollment data
$course_data = $conn->query("
    SELECT courses.course_name AS course_name, COUNT(enrollments.id) AS enrollment_count 
    FROM courses 
    LEFT JOIN enrollments ON courses.id = enrollments.course_id 
    GROUP BY courses.id
");

$course_names = [];
$enrollment_counts = [];
while ($row = $course_data->fetch_assoc()) {
    $course_names[] = $row['course_name'];
    $enrollment_counts[] = $row['enrollment_count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div>Admin Dashboard</div>
        <div>
            <a href="admin_dashboard.php">Home</a>
            <a href="add_admin.php">Add Admin</a>
            <a href="admin_logout.php" class="logout">Logout</a>
        </div>
    </div>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Welcome to the Admin Dashboard</h1>
        <p>Manage all aspects of the university system</p>
    </div>

    <!-- Widgets -->
    <div class="widgets">
        <div class="widget">
            <h2><?php echo $total_students; ?></h2>
            <p>Total Students</p>
        </div>
        <div class="widget">
            <h2><?php echo $total_courses; ?></h2>
            <p>Total Courses</p>
        </div>
        <div class="widget">
            <h2><?php echo $total_enrollments; ?></h2>
            <p>Total Enrollments</p>
        </div>
    </div>

    <!-- Cards for Sections -->
    <div class="cards">
        <div class="card">
            <h3>Manage Students</h3>
            <a href="student_logic_admin/manage_student.php">View All</a>
        </div>
        <div class="card">
            <h3>Manage Courses</h3>
            <a href="courses_logic_admin/manage_courses.php">View All</a>
        </div>
        <div class="card">
            <h3>Manage Enrollments</h3>
            <a href="enrollments_logic_admin/manage_enrollments.php">View All</a>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="enrollmentChart"></canvas>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 University Management System. All rights reserved.
    </div>

    <!-- Chart.js Script -->
    <script>
        // Enrollment data from PHP
        const courseNames = <?php echo json_encode($course_names); ?>;
        const enrollmentCounts = <?php echo json_encode($enrollment_counts); ?>;

        // Create the chart
        const ctx = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Bar chart
            data: {
                labels: courseNames, // Course names
                datasets: [{
                    label: 'Enrollments per Course',
                    data: enrollmentCounts, // Enrollment counts
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
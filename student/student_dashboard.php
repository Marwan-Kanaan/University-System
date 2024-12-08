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

// Fetch enrolled courses
$course_query = "SELECT courses.course_name, courses.course_code, enrollments.enrollment_date
                 FROM enrollments
                 JOIN courses ON enrollments.course_id = courses.id
                 WHERE enrollments.student_id = ?";
$course_stmt = $conn->prepare($course_query);
$course_stmt->bind_param("i", $student_id);
$course_stmt->execute();
$courses_result = $course_stmt->get_result();

// Fetch similar students (students enrolled in the same courses)
$similar_students_query = "
    SELECT students.id, students.name, GROUP_CONCAT(courses.course_name ORDER BY courses.course_name) AS similar_courses
    FROM enrollments
    JOIN courses ON enrollments.course_id = courses.id
    JOIN students ON enrollments.student_id = students.id
    WHERE courses.id IN (SELECT course_id FROM enrollments WHERE student_id = ?)
    AND students.id != ?
    GROUP BY students.id";
$similar_stmt = $conn->prepare($similar_students_query);
$similar_stmt->bind_param("ii", $student_id, $student_id);
$similar_stmt->execute();
$similar_students_result = $similar_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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

        /* Profile Section */
        .profile-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #2980b9;
            margin-left: 10px;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            text-align: center;
        }

        .welcome-message {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-top: 40px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            color: #3498db;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        /* Calendar Styling */
        .calendar {
            margin-top: 40px;
            width: 100%;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            justify-items: center;
        }

        .calendar div {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .calendar div:hover {
            background-color: #2c3e50;
            color: white;
        }

        .current-day {
            background-color: #2980b9;
            color: white;
            font-weight: bold;
        }

        .similar-students {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-items: center;
        }

        .student-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 200px;
        }

        .student-card h3 {
            margin: 10px 0;
            color: #2c3e50;
        }

        .student-card p {
            color: #7f8c8d;
            font-size: 14px;
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

            .welcome-message {
                font-size: 28px;
            }

            .similar-students {
                grid-template-columns: 1fr;
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
        <div class="profile-container">
            <!-- Display the welcome message with profile photo beside -->
            <div class="welcome-message">
                Welcome, <?php echo htmlspecialchars($student['name']); ?>
                <img src="<?php echo $profile_photo_url; ?>" alt="Profile Photo" class="profile-photo">
            </div>
        </div>

        <h2>Your Enrolled Courses</h2>

        <!-- Courses Table -->
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Code</th>
                    <th>Enrollment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = $courses_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                        <td><?php echo $course['enrollment_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br><br><br>

        <!-- Calendar Section -->
        <div class="calendar" id="calendar"></div>

        <br><br><br><br>

        <h3>Students with Similar Courses</h3>
        <div class="similar-students">
            <?php while ($similar_student = $similar_students_result->fetch_assoc()): ?>
                <div class="student-card">
                    <h3><?php echo htmlspecialchars($similar_student['name']); ?></h3>
                    <p>Enrolled in: <?php echo htmlspecialchars($similar_student['similar_courses']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // JavaScript to generate the current month calendar
        const calendarElement = document.getElementById('calendar');

        function generateCalendar() {
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
            const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);
            const daysInMonth = lastDayOfMonth.getDate();
            const firstDayWeekday = firstDayOfMonth.getDay();

            // Clear the calendar
            calendarElement.innerHTML = '';

            // Add the days of the week above the calendar
            const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            daysOfWeek.forEach(day => {
                const dayCell = document.createElement('div');
                dayCell.textContent = day;
                calendarElement.appendChild(dayCell);
            });

            // Adjust firstDayWeekday to match Monday as the first day
            const adjustedFirstDayWeekday = firstDayWeekday === 0 ? 6 : firstDayWeekday - 1;

            // Add empty cells for the days before the first day of the month
            for (let i = 0; i < adjustedFirstDayWeekday; i++) {
                const emptyCell = document.createElement('div');
                calendarElement.appendChild(emptyCell);
            }

            // Add the days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayCell = document.createElement('div');
                dayCell.textContent = day;
                if (day === today.getDate()) {
                    dayCell.classList.add('current-day');
                }
                calendarElement.appendChild(dayCell);
            }
        }

        // Generate the calendar on page load
        generateCalendar();
    </script>
</body>

</html>
<?php
// Start session
session_start();

// Include the database connection
include('../includes/db.php');

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } else {
        // Check if the admin exists in the database
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $admin['password'])) {
                // Start session for admin
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];

                // Redirect to the dashboard
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "Admin not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin_login.css">
</head>

<body>
    <div class="card">
        <h1>Admin Login</h1>

        <!-- Show error message if any -->
        <?php if ($message != '') { ?>
            <div class="error"><?php echo $message; ?></div>
        <?php } ?>

        <!-- Login form -->
        <form action="admin_login.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="button">Login</button>
        </form>

        <!-- Link to go back to the home page -->
        <div class="link">
            <p>not an admin?<a href="../student/login.php"> Go back</a></p>
        </div>
    </div>
</body>

</html>
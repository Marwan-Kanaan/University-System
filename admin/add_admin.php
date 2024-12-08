<?php
// Include the database connection
include('../includes/db.php');

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($address) || empty($phone)) {
        $message = "All fields are required.";
    } else {
        // Insert the new admin into the database
        $stmt = $conn->prepare("INSERT INTO admins (name, email, password, address, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $password, $address, $phone);

        if ($stmt->execute()) {
            $message = "Admin added successfully!";
        } else {
            $message = "Error adding admin. Please try again.";
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
    <title>Add Admin</title>
    <link rel="stylesheet" href="../css/add_admin.css">
</head>

<body>

    <!-- Add Admin Card -->
    <div class="card">
        <h2>Add New Admin</h2>

        <!-- Display message -->
        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <!-- Add Admin Form -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <button type="submit">Add Admin</button>
        </form>

        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>

</body>

</html>
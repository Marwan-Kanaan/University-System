<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit;
}

// Include the database connection
include "../../includes/db.php";

// Check if the enrollment ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $enrollment_id = $_GET['id'];

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM enrollments WHERE id = ?");
    $stmt->bind_param("i", $enrollment_id);

    if ($stmt->execute()) {
        // Redirect to the manage enrollments page with a success message
        $_SESSION['success'] = "Enrollment deleted successfully.";
        header('Location: manage_enrollments.php');
        exit;
    } else {
        // Redirect to the manage enrollments page with an error message
        $_SESSION['error'] = "Failed to delete the enrollment.";
        header('Location: manage_enrollments.php');
        exit;
    }
} else {
    // Redirect to the manage enrollments page if no ID is provided
    $_SESSION['error'] = "No enrollment ID provided.";
    header('Location: manage_enrollments.php');
    exit;
}
?>

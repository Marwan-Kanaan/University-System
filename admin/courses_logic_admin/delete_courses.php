<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit;
}

// Include the database connection
include('../../includes/db.php');

// Check if the course ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $course_id = $_GET['id'];

    // Start a transaction to ensure both deletions occur
    $conn->begin_transaction();

    try {
        // Delete all enrollments related to this course
        $delete_enrollments_query = "DELETE FROM enrollments WHERE course_id = ?";
        $stmt = $conn->prepare($delete_enrollments_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Delete the course
        $delete_course_query = "DELETE FROM courses WHERE id = ?";
        $stmt = $conn->prepare($delete_course_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $conn->commit();

        // Redirect to the manage courses page with a success message
        $_SESSION['success'] = "Course and related enrollments deleted successfully.";
        header('Location: manage_courses.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();

        // Redirect to the manage courses page with an error message
        $_SESSION['error'] = "Failed to delete the course: " . $e->getMessage();
        header('Location: manage_courses.php');
        exit;
    }
} else {
    // Redirect to the manage courses page if no ID is provided
    $_SESSION['error'] = "No course ID provided.";
    header('Location: manage_courses.php');
    exit;
}
?>

<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit;
}

// Include the database connection
include('../../includes/db.php');

// Check if the student ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];

    // Begin a transaction to ensure both deletions happen together
    $conn->begin_transaction();

    try {
        // First, delete all enrollments associated with the student
        $delete_enrollments_query = "DELETE FROM enrollments WHERE student_id = ?";
        $stmt_enrollments = $conn->prepare($delete_enrollments_query);
        $stmt_enrollments->bind_param("i", $student_id);
        $stmt_enrollments->execute();
        $stmt_enrollments->close();

        // Then, delete the student
        $delete_student_query = "DELETE FROM students WHERE id = ?";
        $stmt_student = $conn->prepare($delete_student_query);
        $stmt_student->bind_param("i", $student_id);
        $stmt_student->execute();
        $stmt_student->close();

        // Commit the transaction
        $conn->commit();

        // Redirect to the manage students page with a success message
        $_SESSION['success'] = "Student and associated enrollments deleted successfully.";
        header('Location: manage_student.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $conn->rollback();
        $_SESSION['error'] = "Failed to delete the student and their enrollments.";
        header('Location: manage_student.php');
        exit;
    }
} else {
    // Redirect to the manage students page if no ID is provided
    $_SESSION['error'] = "No student ID provided.";
    header('Location: manage_student.php');
    exit;
}
?>

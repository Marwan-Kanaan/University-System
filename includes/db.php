<?php
// Database configuration
$host = 'localhost'; // Database server (usually localhost)
$dbname = 'university_management'; // Your database name
$username = 'root'; // Database username
$password = ''; // Database password (usually empty for local development)

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

<?php
session_start();


include('../includes/db.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Check if the user exists in the database
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user data in session variables
                $_SESSION['student_id'] = $user['id'];
                $_SESSION['student_name'] = $user['name'];

                header("Location: student_dashboard.php");
                exit();
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }


        .card {
            background-color: white;
            padding: 50px;
            width: 400px;
            height: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }


        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
        }


        h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }


        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            font-size: 20px;
            padding: 18px 40px;
            margin: 15px 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        .button:active {
            transform: scale(1);
        }


        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }

        .link {
            margin-top: 20px;
            font-size: 16px;
        }

        .link a {
            color: #3498db;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            .profile-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Student Login</h1>


        <?php if ($error != '') { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>


        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="button">Login</button>
        </form>


        <div class="link">
            <p>Are you an admin? <a href="../admin/admin_login.php">Log in here</a></p>
        </div>
    </div>
</body>

</html>
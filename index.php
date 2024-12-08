<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to University Management</title>
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

        /* Card container */
        .card {
            background-color: white;
            padding: 50px;
            width: 400px;
            /* Same width as height for square card */
            height: 400px;
            /* Same height for a square card */
            border-radius: 15px;
            /* Rounded corners */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            /* Soft shadow for better depth */
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Header styles */
        h1 {
            color: #2c3e50;
            font-size: 28px;
            /* Large font size for the header */
            margin-bottom: 20px;
            line-height: 1.4;
            font-weight: bold;
            /* Make the header bold */
        }

        /* Paragraph styles */
        p {
            color: #7f8c8d;
            font-size: 16px;
            /* Medium size font for the paragraph */
            margin-bottom: 30px;
            line-height: 1.6;
            font-weight: normal;
            /* Regular weight for the paragraph text */
        }

        /* Button styles */
        .button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            font-size: 20px;
            /* Larger text on the buttons */
            padding: 18px 40px;
            margin: 15px 10px;
            /* More spacing between buttons */
            border: none;
            border-radius: 8px;
            /* More rounded buttons */
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
            transform: scale(1.05);
            /* Slightly enlarge button on hover */
        }

        .button:active {
            transform: scale(1);
            /* Button returns to normal size when clicked */
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Welcome to the University Management System</h1>
        <p>Manage student records, enroll in courses, and more!</p>

        <a href="student/login.php" class="button">Log In</a>
    </div>
</body>

</html>
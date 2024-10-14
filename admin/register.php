<?php
session_start();
include("../db.php");

if (isset($_POST['register'])) {
    $admin_name = mysqli_real_escape_string($con, $_POST['admin_name']);
    $admin_email = mysqli_real_escape_string($con, $_POST['admin_email']);
    $admin_password = password_hash(mysqli_real_escape_string($con, $_POST['admin_password']), PASSWORD_DEFAULT);

    // Insert admin info into the database
    $query = "INSERT INTO admin_info (admin_name, admin_email, admin_password) VALUES ('$admin_name', '$admin_email', '$admin_password')";
    if (mysqli_query($con, $query)) {
        echo "<script>alert('Registration successful! You can log in now.'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <style>
        /* Basic reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: Arial, sans-serif;
    background-image: url("../img/icon/icon.png");
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-size: 100%;
    background-attachment: fixed;
    color: #333; /* Dark text color */
    background-color: #f4f4f4; /* Light background for contrast */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full viewport height */
}

/* Container for the form */
.register-container {
    background-color: #fff; /* White background for form */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    padding: 20px; /* Inner spacing */
    width: 400px; /* Fixed width for the form */
}

/* Header for the form */
h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333; /* Dark text color */
}

/* Label styling */
label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block; /* Each label on a new line */
}

/* Input field styling */
input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%; /* Full width */
    padding: 10px; /* Inner padding */
    margin-bottom: 15px; /* Space between inputs */
    border: 1px solid #ccc; /* Light border */
    border-radius: 5px; /* Rounded edges */
}

/* Button styling */
input[type="submit"] {
    background-color: #28a745; /* Bootstrap success color */
    color: white; /* White text */
    border: none; /* No border */
    border-radius: 5px; /* Rounded edges */
    padding: 10px; /* Inner padding */
    cursor: pointer; /* Pointer cursor on hover */
    width: 100%; /* Full width */
    font-size: 16px; /* Increase font size */
}

/* Button hover effect */
input[type="submit"]:hover {
    background-color: #218838; /* Darker shade for hover */
}

/* Error message styling */
p.error {
    color: red; /* Red color for error messages */
    text-align: center; /* Center align error messages */
}

    </style>
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="admin_email">Email:</label>
        <input type="email" id="admin_email" name="admin_email" required>
        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password" required>
        <input type="submit" name="register" value="Register">
    </form>
</div>
</body>
</html>

<?php
session_start();
include("../db.php");

if (isset($_POST['login'])) {
    $admin_email = mysqli_real_escape_string($con, $_POST['admin_email']);
    $admin_password = mysqli_real_escape_string($con, $_POST['admin_password']);

    // Check if admin exists
    $query = "SELECT * FROM admin_info WHERE admin_email='$admin_email'";
    $result = mysqli_query($con, $query);
    $admin = mysqli_fetch_assoc($result);

    // Verify password
    if ($admin && password_verify($admin_password, $admin['admin_password'])) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name'];
        header("Location: index.php"); // Redirect to orders page after successful login
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
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
    background-size: cover;
    background-position: center;
    color: #333;
    background-color: #f4f4f4; /* Light background for contrast */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Container for the form */
.login-container {
    background-color: #fff; /* White background for form */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    padding: 20px; /* Inner spacing */
    width: 300px; /* Fixed width for the form */
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
    background-color: #007bff; /* Bootstrap primary color */
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
    background-color: #0056b3; /* Darker shade for hover */
}

/* Error message styling */
p.error {
    color: red; /* Red color for error messages */
    text-align: center; /* Center align error messages */
}
.btn-primary {
    background-color: green; /* Bootstrap primary color */
    color: white; /* White text */
    border: none; /* No border */
    border-radius: 5px; /* Rounded edges */
    padding: 10px; /* Inner padding */
    cursor: pointer; /* Pointer cursor on hover */
    width: 100%; /* Full width */
    font-size: 16px; /* Increase font size */
    text-decoration: none;
    margin-top: 10px;
    display: block;
    text-align: center;
}
    </style>
</head>
<body>
   <div class="login-container">
    <h2>Admin Login</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <label for="admin_email">Email:</label>
        <input type="email" id="admin_email" name="admin_email" required>
        <label for="admin_password">Password:</label>
        <input type="password" id="admin_password" name="admin_password" required>
        <input type="submit" name="login" value="Login"> <br> <br>
        <button class="btn btn-primary"><a style="text-decoration: none; color: white;" href="../index.php">View Website</a></button>
        <!-- <p>Don't have an account? <a href="register.php">Register here</a>.</p> -->
    </form>
</div>
    
</body>
</html>

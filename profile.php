<?php
session_start();
include "db.php"; // Include your database connection

if (!isset($_SESSION["uid"])) {
    // If the user is not logged in, redirect to the login page
    header("location:index.php");
    exit();
}

$uid = $_SESSION["uid"];
$sql = "SELECT * FROM user_info WHERE user_id='$uid'";
$query = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($query);

// Update profile details when form is submitted
if (isset($_POST['update_profile'])) {
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $address1 = mysqli_real_escape_string($con, $_POST['address1']);
    $address2 = mysqli_real_escape_string($con, $_POST['address2']);
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : $user['password'];

    // Update the user info in the database
    $update_sql = "UPDATE user_info SET first_name='$first_name', last_name='$last_name', email='$email', mobile='$mobile', address1='$address1', address2='$address2', password='$password' WHERE user_id='$uid'";
    if (mysqli_query($con, $update_sql)) {
        // Reload updated user info
        $sql = "SELECT * FROM user_info WHERE user_id='$uid'";
        $query = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($query);
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating profile!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profile - Queen Online Thrift Store</title>

        <!-- Google font -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

        <!-- Bootstrap -->
        <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

        <!-- Font Awesome Icon -->
        <link rel="stylesheet" href="css/font-awesome.min.css">

        <!-- Custom styles -->
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
    </head>
    <body>
        <!-- Include the header -->
        <?php include('header.php'); ?>

        <!-- Profile Section -->
        <div class="container" style="margin-top: 50px;">
            <h2>My Profile</h2>
            <div class="row">
                <div class="col-md-6">
                    <form action="" method="POST">
                        <table class="table table-striped">
                            <tr>
                                <th>First Name</th>
                                <td><input type="text" name="first_name" class="form-control" value="<?php echo $user['first_name']; ?>" required></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><input type="text" name="last_name" class="form-control" value="<?php echo $user['last_name']; ?>" required></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td><input type="text" name="mobile" class="form-control" value="<?php echo $user['mobile']; ?>" required></td>
                            </tr>
                            <tr>
                                <th>Address 1</th>
                                <td><input type="text" name="address1" class="form-control" value="<?php echo $user['address1']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Address 2</th>
                                <td><input type="text" name="address2" class="form-control" value="<?php echo $user['address2']; ?>"></td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><input type="password" name="password" class="form-control" placeholder="Enter new password if you want to change"></td>
                            </tr>
                        </table>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Include the footer -->
        <?php include('footer.php'); ?>

        <!-- Bootstrap JS, Popper.js, and jQuery -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

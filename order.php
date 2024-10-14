<?php
session_start();
include "db.php"; // Include your database connection

if (!isset($_SESSION["uid"])) {
    // If the user is not logged in, redirect to the login page
    header("location:index.php");
    exit();
}

$uid = $_SESSION["uid"];

// Fetch user info
$sql = "SELECT * FROM user_info WHERE user_id='$uid'";
$query = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($query);

// Fetch user payment orders
$order_sql = "SELECT * FROM mpesa_payments WHERE user_id='$uid' ORDER BY user_id DESC"; // Adjust the column name 'id' based on your table schema
$order_query = mysqli_query($con, $order_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Payments - Queen Online Thrift Store</title>

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

    <!-- Payments Section -->
    <div class="container" style="margin-top: 50px;">
        <h2>My Payments</h2>
        <div class="row">
            <div class="col-md-12">
                <?php if (mysqli_num_rows($order_query) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment ID</th> <!-- Change header to reflect payment -->
                                <th>Full Name</th>
                                <th>Phone Number</th>
                                <th>Amount</th>
                                <th>Status</th> <!-- Adjust according to your payment status if applicable -->
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($order_query)): ?>
                                <tr>
                                    <td><?php echo $order['payment_id']; ?></td> <!-- Adjust if your ID column is named differently -->
                                    <td><?php echo $order['full_name']; ?></td>
                                    <td><?php echo $order['mpesa_phone']; ?></td>
                                    <td><?php echo $order['mpesa_amount']; ?></td>
                                    <td><?php echo 'completed'; ?></td> <!-- Change if needed -->
                                    <td><button class="btn btn-primary"><a href="order_details.php?payment_id=<?php echo $order['payment_id']; ?>">View Details</a></button></td> <!-- Link to payment details -->
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">You have no payment records yet.</div>
                <?php endif; ?>
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

<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlineshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the payment ID from the URL
$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : die("Error: Payment ID not provided.");

// Fetch payment details for the specific payment ID
$payment_details_sql = "SELECT * FROM mpesa_payments WHERE payment_id = ?";
$stmt = $conn->prepare($payment_details_sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();

// Include header
include('header.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <?php
        // Check if there are details
        if ($result->num_rows > 0) {
            $payment = $result->fetch_assoc(); // Fetch the payment details

            echo "<h2>Payment Details for Payment ID: " . htmlspecialchars($payment_id) . "</h2>";
            echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>ZIP</th>
                            <th>Amount</th>
                            <th>Phone Number</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>" . htmlspecialchars($payment['full_name']) . "</td>
                            <td>" . htmlspecialchars($payment['email']) . "</td>
                            <td>" . htmlspecialchars($payment['address']) . "</td>
                            <td>" . htmlspecialchars($payment['city']) . "</td>
                            <td>" . htmlspecialchars($payment['state']) . "</td>
                            <td>" . htmlspecialchars($payment['zip']) . "</td>
                            <td>" . htmlspecialchars($payment['mpesa_amount']) . "</td>
                            <td>" . htmlspecialchars($payment['mpesa_phone']) . "</td>
                            <td>" . htmlspecialchars($payment['order_date']) . "</td>
                        </tr>
                    </tbody>
                  </table>";

            // Add a print/download button
            // echo '<a href="generate_receipt.php" class="btn btn-primary">Download Receipt (PDF)</a>';
        } else {
            echo "<h2>No details found for this payment.</h2>";
        }

        // Close connections
        $stmt->close();
        $conn->close();
        ?>
        <a href="order.php" class="btn btn-primary">Back to Your Payments</a>
    </div>

    <!-- Include footer -->
    <?php include('footer.php'); ?>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>

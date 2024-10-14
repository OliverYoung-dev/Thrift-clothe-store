<?php
session_start();
if (isset($_POST['submit'])) {
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

    date_default_timezone_set('Africa/Nairobi');

    // M-Pesa API credentials
    $consumerKey = 'oG6SezHGWUxAijCFMxlVVJ04ODJBNI1DOBO8LoY1RaFAzeHB';
    $consumerSecret = 'lH0IduuFATAb6QQRwEwJS53RxjkfGdQra35LYOJZf2AGErZjEFX9zyGI2paAUeS6';

    $BusinessShortCode = '174379';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

    // Payment details
    $PartyA = $_POST['phone'];  // Phone number to receive the STK push
    $AccountReference = '2255';
    $TransactionDesc = 'Checkout Payment';
    $Amount = $_POST['amount'];

    // Generate timestamp and password for the request
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

    // Access token
    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);

    // Initiate the STK push
    $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

    $curl_post_data = [
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => 'https://yourcallbackurl.com',  
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    ];

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    $response = json_decode($curl_response, true);

    // Check if the response contains an error
    if (isset($response['errorCode'])) {
        echo "Error: " . $response['errorMessage'];
    } else {
        // Save transaction details in the database
        // Assuming you have collected the required user details before this section
        $user_id = $_SESSION["uid"];
        $full_name = $_POST['firstname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $mpesa_amount = $_POST['mpesa_amount'];
        $mpesa_phone = $_POST['mpesa_phone'];
    // Ensure this is collected from your form

// Update the prepared statement and bind parameters
$stmt = $conn->prepare("INSERT INTO mpesa_payments 
    (user_id, full_name, email, address, city, state, zip, mpesa_amount, mpesa_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Now bind the parameters. Note the order and number should match the statement
$stmt->bind_param("issssssss", $user_id, $full_name, $email, $address, $city, $state, $zip, $Amount, $PartyA);
$stmt->execute();
$stmt->close();


        // After successful payment, delete cart items for the user
        $user_id = $_SESSION["uid"];
        $delete_cart_sql = "DELETE FROM cart WHERE user_id = '$user_id'";
        if (mysqli_query($conn, $delete_cart_sql)) {
            echo "<script>alert('Payment successful. Please check your phone for the payment confirmation.');</script>";
        }

        // Redirect to the thank-you page
        header("Location: store.php");
        exit();
    }

    curl_close($curl);
    $conn->close();
}
?>

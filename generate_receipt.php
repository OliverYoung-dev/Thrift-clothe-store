<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION["uid"])) {
    header("Location: login.php");
    exit();
}

// Include FPDF library
require('fpdf/fpdf.php');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlineshop";

$conn = new mysqli($servername, $username, $password, $dbname);

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
$payment = $result->fetch_assoc();

// Create PDF document
$pdf = new FPDF();
$pdf->AddPage();

// Shop details
$shop_name = "Queen Online Thrift";
$shop_address = "123 Thrift Street, Nairobi";
$shop_phone = "+254 789 061 685";

// Add shop details to the PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, $shop_name, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $shop_address, 0, 1, 'C');
$pdf->Cell(0, 10, "Tel: " . $shop_phone, 0, 1, 'C');
$pdf->Ln(10); // Line break

// Add receipt title
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Payment Receipt', 0, 1, 'C');
$pdf->Ln(10);

// Add payment details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Full Name:', 1);
$pdf->Cell(0, 10, $payment['full_name'], 1, 1);
$pdf->Cell(50, 10, 'Email:', 1);
$pdf->Cell(0, 10, $payment['email'], 1, 1);
$pdf->Cell(50, 10, 'Address:', 1);
$pdf->Cell(0, 10, $payment['address'], 1, 1);
$pdf->Cell(50, 10, 'City:', 1);
$pdf->Cell(0, 10, $payment['city'], 1, 1);
$pdf->Cell(50, 10, 'State:', 1);
$pdf->Cell(0, 10, $payment['state'], 1, 1);
$pdf->Cell(50, 10, 'ZIP:', 1);
$pdf->Cell(0, 10, $payment['zip'], 1, 1);
$pdf->Cell(50, 10, 'Phone Number:', 1);
$pdf->Cell(0, 10, $payment['mpesa_phone'], 1, 1);
$pdf->Cell(50, 10, 'Order Date:', 1);
$pdf->Cell(0, 10, $payment['order_date'], 1, 1);
$pdf->Cell(50, 10, 'Amount:', 1);
$pdf->Cell(0, 10, 'KSh ' . $payment['mpesa_amount'], 1, 1);

// Add barcode image (optional if you want to show a barcode, replace 'barcode.png' with your image)
$pdf->Ln(10);
$pdf->Image('barcode.png', 80, null, 50, 20); // Adjust image position and size

// Add footer
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');

// Output the PDF
$pdf->Output('D', 'Receipt_' . $payment_id . '.pdf'); // 'D' to force download
exit;
?>

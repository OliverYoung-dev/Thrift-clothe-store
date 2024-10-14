<?php
include("../db.php");

if (isset($_POST['brand_id']) && isset($_POST['brand_title'])) {
    $brand_id = $_POST['brand_id'];
    $brand_title = $_POST['brand_title'];

    // Update the brand in the database
    $query = "UPDATE brands SET brand_title = '$brand_title' WHERE brand_id = '$brand_id'";
    if (mysqli_query($con, $query)) {
        header("Location: brands.php?success=1");
    } else {
        die("Update query failed: " . mysqli_error($con));
    }
}
?>

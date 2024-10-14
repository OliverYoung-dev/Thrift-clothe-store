<?php
include("../db.php");

if (isset($_POST['cat_id']) && isset($_POST['cat_title'])) {
    $cat_id = $_POST['cat_id'];
    $cat_title = $_POST['cat_title'];

    $sql = "UPDATE categories SET cat_title='$cat_title' WHERE cat_id='$cat_id'";
    mysqli_query($con, $sql) or die("Update query is incorrect...");

    header("Location: your_categories_page.php");  // Redirect back to categories page
    exit;
}
?>

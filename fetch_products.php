<?php
include 'db.php'; // Make sure this file is included and $pdo is set correctly

if (isset($_POST['search']) && isset($_POST['category'])) {
    $search = '%' . $_POST['search'] . '%';
    $category = $_POST['category'];

    // Prepare the query
    $sql = "SELECT * FROM products WHERE product_title LIKE :search";
    
    if ($category !== 'all') {
        $sql .= " AND product_cat = :category";
    }

    $stmt = $pdo->prepare($sql);

    // Bind parameters manually
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);

    if ($category !== 'all') {
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
    }

    // Execute the statement
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $product) {
            echo '<div class="col-md-4">';
            echo '<img src="' . $product['product_image'] . '" alt="' . $product['product_title'] . '">';
            echo '<p>' . $product['product_title'] . '</p>';
            echo '<p>Price: ' . $product['product_price'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }
}
?>

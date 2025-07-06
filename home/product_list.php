<?php
// Include database connection
include '../db_connect.php';

// Get the category IDs and search query from the URL
$category_ids = isset($_GET['category_id']) ? explode(',', $_GET['category_id']) : [];
$search_query = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Modify the query to support multiple category IDs
if (!empty($category_ids)) {
    // Create placeholders for the category IDs
    $category_placeholders = implode(',', array_fill(0, count($category_ids), '?'));
    $query = "SELECT p.product_id, p.title, p.price, 
              COALESCE(pi.image_path, 'path/to/default_image.jpg') AS image_path 
              FROM products p
              LEFT JOIN product_images pi ON p.product_id = pi.product_id
              WHERE p.category_id IN ($category_placeholders) AND p.title LIKE ?
              GROUP BY p.product_id";
} else {
    // Default query to show all products if no category is selected
    $query = "SELECT p.product_id, p.title, p.price, 
              COALESCE(pi.image_path, 'path/to/default_image.jpg') AS image_path 
              FROM products p
              LEFT JOIN product_images pi ON p.product_id = pi.product_id
              WHERE p.title LIKE ?
              GROUP BY p.product_id";
}

// Prepare the SQL statement
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters: categories are integers, and search query is a string
if (!empty($category_ids)) {
    $types = str_repeat('i', count($category_ids)) . 's';  // 'i' for category IDs, 's' for search query
    $params = array_merge($category_ids, [$search_query]);
    $stmt->bind_param($types, ...$params);
} else {
    // If no category IDs are provided, only bind the search query
    $stmt->bind_param('s', $search_query);
}

// Execute the query
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="home.css"> 
</head>
<body>
    <?php include('header.php'); ?>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="">
            <input type="hidden" name="category_id" value="<?= isset($_GET['category_id']) ? htmlspecialchars($_GET['category_id']) : '' ?>">
            <input type="text" name="search" placeholder="Search products..." 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Product Container -->
    <div class="product-container">
        <?php if ($result->num_rows > 0): ?>
            <div class="product-list">
                <?php while ($product = $result->fetch_assoc()): ?>
                    <a href="product_details.php?id=<?= $product['product_id'] ?>" class="product-card">
                        <div class="product-image-card">
                            <img src="<?= htmlspecialchars($product['image_path']) ?>" 
                                 alt="<?= htmlspecialchars($product['title']) ?>">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['title']) ?></h3>
                            <p class="price"><?= number_format($product['price'], 2) ?> INR</p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No products found in these categories or matching the search query.</p>
        <?php endif; ?>
    </div>

    <?php
    // Free result and close the statement
    $result->free();
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>

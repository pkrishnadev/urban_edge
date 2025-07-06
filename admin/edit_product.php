<?php
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}
// Connect to the database
include '../db_connect.php';

// Get product ID from query parameter
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if ($product_id) {
    // Fetch the product details from the database
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found!";
        exit;
    }

    // Fetch the images from the product_images table
    $image_query = "SELECT image_path FROM product_images WHERE product_id = ?";
    $stmt = $conn->prepare($image_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $image_result = $stmt->get_result();
    $images = $image_result->fetch_all(MYSQLI_ASSOC); // Fetch as an associative array
} else {
    echo "No product ID provided!";
    exit;
}

// Handle form submission to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_category = $_POST['product_category'];
    $product_size_s = $_POST['product_size_s'];
    $product_size_m = $_POST['product_size_m'];
    $product_size_l = $_POST['product_size_l'];
    $product_size_xl = $_POST['product_size_xl'];
    $product_size_xxl = $_POST['product_size_xxl'];

    // Update the product details in the database
    $query = "UPDATE products 
              SET title = ?, description = ?, price = ?, category_id = ?, stock_s = ?, stock_m = ?, stock_l = ?, stock_xl = ?, stock_xxl = ?
              WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssdiisssii", 
        $product_name, 
        $product_description, 
        $product_price, 
        $product_category, 
        $product_size_s, 
        $product_size_m, 
        $product_size_l, 
        $product_size_xl, 
        $product_size_xxl, 
        $product_id
    );
    $stmt->execute();


    $category_id = $product_category;


    $category_name = ($category_id == 2000) ? 'plain' : 'printed';

    header("Location: product_list.php?category=" . urlencode($category_name));
    exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php" class="outline-btn">Home</a></li>
            <li><a href="add_product.php" class="outline-btn">Add Product</a></li>
            <li><a href="order_details.php" class="outline-btn">Order Details</a></li>
            <li><a href="products.php" class="outline-btn">Products</a></li>
            <li><a href="user_details.php" class="outline-btn">User List</a></li>
            <li><button class="outline-btn" id="logoutBtn">Logout</button></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="header">
            <h1>Edit Product</h1>
        </div>
        
        <!-- Form for editing a product -->
        <form id="editProductForm" action="" method="POST">

            <!-- Product Images Preview -->
            <div class="form-group">
                <h3>Product Images:</h3>
                <div id="imagePreview" class="image-preview">
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $image): ?>
                            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Product Image" style="max-width: 100px; margin: 5px;">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No images uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Name -->
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" name="product_name" id="productName" value="<?php echo htmlspecialchars($product['title']); ?>" required>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="productDescription">Product Description</label>
                <textarea name="product_description" id="productDescription" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <!-- Size Selection -->
            <div class="form-group">
                <label>Enter Available Quantity for Each Size</label>
                <div class="size-row">
                    <div class="size-column">
                        <label for="sizeS">S</label>
                        <input type="number" name="product_size_s" id="sizeS" value="<?php echo $product['stock_s']; ?>" min="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeM">M</label>
                        <input type="number" name="product_size_m" id="sizeM" value="<?php echo $product['stock_m']; ?>" min="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeL">L</label>
                        <input type="number" name="product_size_l" id="sizeL" value="<?php echo $product['stock_l']; ?>" min="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeXL">XL</label>
                        <input type="number" name="product_size_xl" id="sizeXL" value="<?php echo $product['stock_xl']; ?>" min="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeXXL">XXL</label>
                        <input type="number" name="product_size_xxl" id="sizeXXL" value="<?php echo $product['stock_xxl']; ?>" min="0">
                    </div>
                </div>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="productPrice">Price</label>
                <input type="number" name="product_price" id="productPrice" value="<?php echo $product['price']; ?>" required>
            </div>

            <!-- Product Category -->
            <div class="form-group">
                <label for="productCategory">Product Category</label>
                <select name="product_category" id="productCategory" required>
                    <option value="2000" <?php if ($product['category_id'] == 2000) echo 'selected'; ?>>Plain</option>
                    <option value="3000" <?php if ($product['category_id'] == 3000) echo 'selected'; ?>>Printed</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-submit">Update Product</button>
            </div>
        </form>
    </div>

</body>
</html>

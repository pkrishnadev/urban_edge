<?php
session_start();
include '../db_connect.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$product_query = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$product_query->bind_param("i", $product_id);
$product_query->execute();
$product_result = $product_query->get_result();

if ($product_result->num_rows == 0) {
    echo "<p>Product not found.</p>";
    exit;
}

$product = $product_result->fetch_assoc();

// Fetch product images
$image_query = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$image_query->bind_param("i", $product_id);
$image_query->execute();
$image_result = $image_query->get_result();
$images = [];
while ($row = $image_result->fetch_assoc()) {
    $images[] = $row['image_path'];
}

// Product sizes
$sizes = [];
foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size) {
    $stock_field = 'stock_' . strtolower($size);
    if ($product[$stock_field] > 0) {
        $sizes[] = $size;
    }
}

// Split reviews into an array
$reviews = array_filter(explode("\n", $product['reviews'] ?? ''));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?></title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('header.php'); ?>

<div class="product-details-container">
    <div class="product-image-gallery">
        <div class="thumbnail-images-vertical">
            <?php foreach ($images as $image) : ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Thumbnail" onclick="changeImage('<?php echo htmlspecialchars($image); ?>')">
            <?php endforeach; ?>
        </div>
        <div class="main-image">
            <img id="mainImage" src="<?php echo htmlspecialchars($images[0] ?? 'path/to/default_image.jpg'); ?>" alt="Main Product Image">
        </div>
    </div>

    <div class="product-deatils-info">
        <h1 class="product-deatils-title"><?php echo htmlspecialchars($product['title']); ?></h1>
        <p class="product-deatils-price">₹<?php echo number_format($product['price'], 2); ?> INR</p>

        <form id="cartForm" onsubmit="addToCart(event)">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
            <label for="size-dropdown">Select Size:</label>
            <select id="size-dropdown" name="size">
                <option value="" disabled selected>Select Size</option>
                <?php foreach ($sizes as $size) : ?>
                    <option value="<?php echo $size; ?>"><?php echo $size; ?></option>
                <?php endforeach; ?>
            </select>
            <button class="add-to-cart" type="submit">Add to Cart</button>
        </form>
        <p id="cart-message" class="cart-message"></p>
    </div>
</div>

<hr class="product-deatils-hr">

<div class="product-details-container">
    <div class="product-description-section">
        <h2>Product Description</h2>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    </div>

    <!-- Size Chart Image Section -->
    <div class="size-chart-section">
        <img src="http://localhost/urban_edge/assets/size.png" alt="Size Chart" class="size-chart-image">
    </div>
</div>

<hr class="product-deatils-hr">

<!-- Reviews Section -->
<div class="product-reviews-section">
    <h2>Customer Reviews</h2>
        <div class="reviews-text">
            <?php foreach ($reviews as $review): ?>
                <p><?php echo htmlspecialchars($review); ?></p>
            <?php endforeach; ?>
        </div>
</div>


<?php include('footer.php'); ?>

<script src="home.js" defer></script>
</body>
</html>

<?php
$product_result->free();
$image_result->free();
$product_query->close();
$image_query->close();
$conn->close();
?>

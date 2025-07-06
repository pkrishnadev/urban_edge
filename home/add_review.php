<?php
include('../db_connect.php');
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the product ID from the URL
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
} else {
    echo "No product selected for review.";
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = mysqli_real_escape_string($conn, $_POST['review']);

    // Update the review in the products table
    $update_query = "
        UPDATE products 
        SET reviews = CONCAT(IFNULL(reviews, ''), '\n', '$review')
        WHERE product_id = '$product_id'
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "Review submitted successfully.";
        header("Location: order.php"); // Redirect to order history after submission
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch the product details for display
$product_query = "SELECT title FROM products WHERE product_id = '$product_id'";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);
?>

<!DOCTYPE html>
<html lang="en">

<body>
<?php include('header.php'); ?>

<div class="review-container">
    <h1>Add a Review</h1>

    <?php if ($product): ?>
        <form method="POST" action="">
            <label for="review">Your Review:</label><br>
            <textarea name="review" id="review" rows="5" cols="50" placeholder="Write your review here..." required></textarea>
            <br><br>
            <button type="submit">Submit Review</button>
        </form>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
</body>
</html>

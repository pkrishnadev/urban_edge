<?php
// Add to cart logic
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to log in first.']);
    exit;
}

include '../db_connect.php';

// Retrieve POST data
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$size = isset($_POST['size']) ? $_POST['size'] : '';

// Validate input
if (empty($product_id) || empty($size)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or size. Please try again.']);
    exit;
}

// Fetch product details from the database
$query = "SELECT price FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    exit;
}

$product = $result->fetch_assoc();
$price = $product['price']; // Used only for total calculation, not stored in session

// Initialize cart if not already present
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['total_amount'] = 0.00;
    $_SESSION['order_id'] = random_int(1000000000, 999999999);
}

// Reference the cart from the session
$cart = &$_SESSION['cart'];

// Generate a unique key for the product and size combination
$key = "$product_id-$size";

// Update cart logic
if (isset($cart[$key])) {

    $cart[$key]['quantity'] += 1;
} else {
    // New product to be added to the cart
    $cart[$key] = [
        'product_id' => $product_id,
        'size' => $size,
        'quantity' => 1,
        'order_item_id' => random_int(10000, 99999) // Generate a 5-digit order item ID
    ];
}

// Update total amount dynamically
$_SESSION['total_amount'] += $price;

// Return success response with updated cart and total amount
echo json_encode([
    'status' => 'success',
    'message' => 'Product added to cart successfully!',
    'cart' => $_SESSION['cart'],
    'total_amount' => $_SESSION['total_amount']
]);

exit;
?>

<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['total_amount'])) {
    $_SESSION['total_amount'] = 0.00;
}

// Check if the cart is empty
$cart_empty = empty($_SESSION['cart']);

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $key = $_POST['key'];
    $quantity = intval($_POST['quantity']);
    
    if (isset($_SESSION['cart'][$key]) && $quantity > 0) {
        $_SESSION['cart'][$key]['quantity'] = $quantity;

        // Recalculate total price for the item
        $_SESSION['cart'][$key]['total_price'] = $_SESSION['cart'][$key]['quantity'] * $_SESSION['cart'][$key]['unit_price'];
    }
    header("Location: cart.php");
    exit;
}

// Handle removing an item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $key = $_POST['key'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]); // Remove item from session cart
    }
    header("Location: cart.php");
    exit;
}

// Initialize variables
$cart = $_SESSION['cart'];
$product_details = [];
$total_price = 0;

// Fetch product details and calculate total price
foreach ($cart as $key => $item) {
    $product_id = $item['product_id'];
    $size = $item['size'];
    
    // Fetch stock details based on product_id and size
    $stock_query = "SELECT stock_s, stock_m, stock_l, stock_xl, stock_xxl FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($stock_query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stock_result = $stmt->get_result();
    $stock = $stock_result->fetch_assoc() ?: [
        'stock_s' => 0,
        'stock_m' => 0,
        'stock_l' => 0,
        'stock_xl' => 0,
        'stock_xxl' => 0,
    ];

    // Fetch product details
    $query = "SELECT p.title, p.price, pi.image_path 
              FROM products p
              LEFT JOIN product_images pi ON p.product_id = pi.product_id
              WHERE p.product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $product_details[$key] = $product;

        $quantity = $item['quantity'];
        $unit_price = floatval($product['price']); // Ensure proper type
        $product_total = $unit_price * $quantity;
        $total_price += $product_total;

        // Update the total price for each product in session
        $_SESSION['cart'][$key]['unit_price'] = $unit_price;
        $_SESSION['cart'][$key]['total_price'] = $product_total;
    }

    // Close the prepared statement
    $stmt->close();
}

// Update the total amount in the session
$_SESSION['total_amount'] = $total_price;

// Format total price for display, ensuring it's a valid float
$formatted_total_price = number_format($_SESSION['total_amount'] ?: 0.00, 2);

// Handle checkout logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_checkout'])) {
    $_SESSION['order_date'] = date('Y-m-d H:i:s');
    header("Location: address_select.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('header.php'); ?>

<div class="cart-container">
    <h1>Your Cart</h1>

    <?php if ($cart_empty): ?>
        <h2>Your cart is empty!</h2>
    <?php else: ?>
        <div class="cart-items">
            <?php foreach ($cart as $key => $item): 
                $product = $product_details[$key] ?? null;
                if ($product) {
                    $quantity = $item['quantity'];
                    $size = $item['size'];
                    $product_total = $item['total_price'] ?? 0.00;

                    // Get the available stock for the selected size
                    $stock_value = $stock['stock_' . strtolower($size)] ?? 0;
                    
                    // Set max quantity based on stock
                    $max_quantity = min($stock_value, 5);
            ?>
                <div class="cart-item">
                    <div class="cart-item-details">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image" class="cart-item-image">
                        <div class="cart-item-info">
                            <h3 class="cart-item-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="cart-item-size">Size: <?php echo htmlspecialchars($size); ?></p>
                            <p class="cart-item-price">₹<?php echo number_format($product['price'], 2); ?></p>
                            <form method="POST" class="cart-update-form" id="cartForm-<?php echo $key; ?>">
                                <label for="quantity-<?php echo $key; ?>">Qty:</label>
                                <select id="quantity-<?php echo $key; ?>" name="quantity" class="quantity-input">
                                    <?php for ($i = 1; $i <= $max_quantity; $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php echo $i == $quantity ? 'selected' : ''; ?>>
                                            <?php echo $i; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="key" value="<?php echo $key; ?>">
                                <button type="submit" name="update_quantity" class="update-btn">Update</button>
                            </form>
                            <p class="cart-item-total" id="item-total-<?php echo $key; ?>">Total: ₹<?php echo number_format($product_total, 2); ?></p>
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <form method="POST" class="remove-item-form">
                            <input type="hidden" name="key" value="<?php echo $key; ?>">
                            <button type="submit" name="remove_item" class="remove-btn">Remove</button>
                        </form>
                    </div>
                </div>
            <?php 
                } 
            endforeach; ?>
        </div>
        <hr>
        <div class="cart-summary">
            <form method="POST">
                <h3>Total Price: ₹<?php echo $formatted_total_price; ?></h3>
                <button type="submit" name="proceed_to_checkout" class="cart-checkout-btn">Proceed to Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>

<script src="home.js" defer></script>
</body>
</html>

<?php
$conn->close();
?>

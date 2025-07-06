<?php
session_start();

// Fetch the total payment amount from the session (instead of cookies)
$paymentAmount = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0.00;  // Default to 0.00 if not set

// Generate a unique payment ID for the session if not already set
if (!isset($_SESSION['payment_id'])) {
    $_SESSION['payment_id'] = 'PAY' . uniqid();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to proceed with the payment.";
    exit;
}

// Pre-fill the payment details (assuming these are retrieved from the session or database)
$cardNumber = isset($_SESSION['card_number']) ? $_SESSION['card_number'] : '';
$expiryDate = isset($_SESSION['expiry_date']) ? $_SESSION['expiry_date'] : '';
$cvv = isset($_SESSION['cvv']) ? $_SESSION['cvv'] : '';
$paypalEmail = isset($_SESSION['paypal_email']) ? $_SESSION['paypal_email'] : '';
$upiId = isset($_SESSION['upi_id']) ? $_SESSION['upi_id'] : '';

// Store selected payment method in session after form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $_SESSION['payment_method'] = $_POST['payment_method'];
    $_SESSION['payment_id'] = rand(1000, 9999);  // Random 4-digit payment ID for simplicity
    header('Location: order_confirmed.php');
    exit;
}

$paymentMethod = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Payment</title>
</head>
<body>
<?php include('header.php'); ?>

<div class="payment-container">
    <h1>Payment Page</h1>
    <div class="payment-summary">
        <h3>Total Amount: ₹<?php echo number_format($paymentAmount, 2); ?></h3>
    </div>

    <form action="" method="POST" id="payment-form">
        <div class="payment-method">
            <label for="payment-method">Select Payment Method:</label>
            <select name="payment_method" id="payment-method" required>
                <option value="credit_card" <?php echo $paymentMethod == 'credit_card' ? 'selected' : ''; ?>>Credit Card</option>
                <option value="debit_card" <?php echo $paymentMethod == 'debit_card' ? 'selected' : ''; ?>>Debit Card</option>
                <option value="paypal" <?php echo $paymentMethod == 'paypal' ? 'selected' : ''; ?>>PayPal</option>
                <option value="upi" <?php echo $paymentMethod == 'upi' ? 'selected' : ''; ?>>UPI</option>
                <option value="cod" <?php echo $paymentMethod == 'cod' ? 'selected' : ''; ?>>Cash on Delivery</option>
            </select>
        </div>

        <div class="payment-details" id="payment-details"></div>

        <button type="submit" class="payment-button">Proceed to Pay</button>
    </form>
</div>

<?php include('footer.php'); ?>

<script>
    const paymentMethodSelect = document.getElementById('payment-method');
    const paymentDetailsDiv = document.getElementById('payment-details');

    paymentMethodSelect.addEventListener('change', function() {
        const selectedMethod = this.value;
        let paymentFields = '';

        if (selectedMethod === 'credit_card' || selectedMethod === 'debit_card') {
            paymentFields = ` 
                <div class="payment-field">
                    <label for="card_number">Card Number:</label>
                    <div class="card-number-container">
                        <input type="text" name="card_number_1" maxlength="4" placeholder="1234" required>
                        <input type="text" name="card_number_2" maxlength="4" placeholder="5678" required>
                        <input type="text" name="card_number_3" maxlength="4" placeholder="9101" required>
                        <input type="text" name="card_number_4" maxlength="4" placeholder="1121" required>
                    </div>
                </div>

                <div class="payment-field">
                    <label for="expiry_date">Expiry Date (MM/YY):</label>
                    <input type="text" name="expiry_date" id="expiry_date" maxlength="5" placeholder="MM/YY" required style="width: 100px;">
                </div>

                <div class="payment-field">
                    <label for="cvv">CVV:</label>
                    <input type="password" name="cvv" maxlength="3" required>
                </div>
            `;
        } else if (selectedMethod === 'paypal') {
            paymentFields = ` 
                <div class="payment-field">
                    <label for="paypal_email">PayPal Email:</label>
                    <input type="email" name="paypal_email" required>
                </div>
            `;
        } else if (selectedMethod === 'upi') {
            paymentFields = ` 
                <div class="payment-field">
                    <label for="upi_id">UPI ID:</label>
                    <input type="text" name="upi_id" required>
                </div>
            `;
        } else if (selectedMethod === 'cod') {
            paymentFields = `<p>Cash on Delivery is selected. Proceed to confirm your order.</p>`;
        }

        paymentDetailsDiv.innerHTML = paymentFields;
    });

    paymentMethodSelect.dispatchEvent(new Event('change'));
    // Add input formatting for expiry date field
    
        const expiryDateInput = document.getElementById('expiry_date');

        expiryDateInput.addEventListener('input', function (e) {
            let value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4); // Add '/' after MM
            }
            this.value = value;
        });

        // Expiry date validation: ensure format MM/YY and date is in the future
        
        // Add auto-switch functionality for all input fields in the form
        document.querySelectorAll('input').forEach((input, index, inputs) => {
            input.addEventListener('input', function () {
                if (this.value.length === this.maxLength && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
</script>

</body>
</html>

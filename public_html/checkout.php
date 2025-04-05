<?php
/**
 * GiggaDev E-commerce Platform
 * Checkout Page
 */

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/stripe-functions.php';

// Check if a product ID is provided
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    // Redirect to cart if no product ID is provided
    header('Location: cart.php');
    exit;
}

$product_id = (int)$_GET['product_id'];

// Get product details
$product = get_product($product_id);

// If product doesn't exist or is not active, redirect to cart
if (!$product || $product['active'] != 1) {
    header('Location: cart.php');
    exit;
}

// Define success and cancel URLs for Stripe Checkout
$success_url = SITE_URL . '/checkout/success.php';
$cancel_url = SITE_URL . '/checkout/cancel.php';

// Create Stripe Checkout Session
$checkout_session = create_checkout_session($product, $success_url, $cancel_url);

// If session creation failed, show error
if (!$checkout_session) {
    $error = 'Unable to create checkout session. Please try again later.';
} else {
    // Store the session ID in the user's session for reference
    $_SESSION['checkout_session_id'] = $checkout_session->id;
}

// Include header
include_once 'templates/header.php';
?>

<div class="checkout-container">
    <div class="container">
        <h1>Checkout</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
            <div class="checkout-summary">
                <h2>Order Summary</h2>
                <div class="product-summary">
                    <div class="product-summary-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-summary-details">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                </div>
                
                <div class="checkout-total">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Tax:</span>
                        <span>$0.00</span>
                    </div>
                    <div class="total-row total-final">
                        <span>Total:</span>
                        <span>$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                </div>
                
                <div class="checkout-actions">
                    <button id="checkout-button" class="checkout-button">Proceed to Payment</button>
                    <a href="cart.php" class="back-to-cart">Back to Cart</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!isset($error)): ?>
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('<?php echo STRIPE_PUBLIC_KEY; ?>');
        const checkoutButton = document.getElementById('checkout-button');
        
        checkoutButton.addEventListener('click', function() {
            // Redirect to Stripe Checkout
            stripe.redirectToCheckout({
                sessionId: '<?php echo $checkout_session->id; ?>'
            }).then(function(result) {
                if (result.error) {
                    // Display error to the customer
                    console.error(result.error.message);
                    alert('Payment error: ' + result.error.message);
                }
            });
        });
    });
</script>
<?php endif; ?>

<?php
// Include footer
include_once 'templates/footer.php';
?>

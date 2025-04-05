<?php
/**
 * GiggaDev E-commerce Platform
 * Homepage
 */

// Include configuration and functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get featured products
$featured_products = get_featured_products(6);

// Include header
include_once 'templates/header.php';
?>

<div class="hero-section">
    <div class="container">
        <h1>Welcome to GiggaDev</h1>
        <p>Your one-stop shop for premium digital products</p>
        <a href="products.php" class="cta-button">Browse Products</a>
    </div>
</div>

<div class="featured-section">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <?php if ($featured_products && count($featured_products) > 0): ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                            <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="product-button">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No featured products available at this time.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="benefits-section">
    <div class="container">
        <h2>Why Choose GiggaDev?</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🔒</div>
                <h3>Secure Checkout</h3>
                <p>Your payments are processed securely with Stripe</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">⚡</div>
                <h3>Instant Delivery</h3>
                <p>Get your digital products immediately after purchase</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">💬</div>
                <h3>24/7 Support</h3>
                <p>Our team is always available to help with any issues</p>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include_once 'templates/footer.php';
?>
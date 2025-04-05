    </main>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-container">
                <div class="footer-column">
                    <h3>GiggaDev</h3>
                    <p>Premium digital products marketplace with secure checkout and instant delivery.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/products.php">Products</a></li>
                        <li><a href="/about.php">About Us</a></li>
                        <li><a href="/contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Customer Support</h3>
                    <ul>
                        <li><a href="/faq.php">FAQ</a></li>
                        <li><a href="/shipping.php">Shipping & Delivery</a></li>
                        <li><a href="/returns.php">Returns & Refunds</a></li>
                        <li><a href="/terms.php">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <ul>
                        <li><a href="mailto:support@giggadev.com">support@giggadev.com</a></li>
                        <li><a href="tel:+18005551234">1-800-555-1234</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> GiggaDev. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <?php if (isset($extra_js) && is_array($extra_js)): ?>
        <?php foreach ($extra_js as $js_file): ?>
            <script src="<?php echo htmlspecialchars($js_file); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
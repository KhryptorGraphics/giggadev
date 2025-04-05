<?php
/**
 * GiggaDev E-commerce Platform
 * Header Template
 */

// Get cart count from session
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' . SITE_TITLE : SITE_TITLE; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description) : SITE_DESCRIPTION; ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/normalize.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <?php if (isset($extra_css) && is_array($extra_css)): ?>
        <?php foreach ($extra_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars($css_file); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.ico">
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <div class="site-logo">
                <a href="/">
                    <img src="/assets/images/giggadev-logo.png" alt="GiggaDev Logo">
                    <span>GiggaDev</span>
                </a>
            </div>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="/" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="/products.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'class="active"' : ''; ?>>Products</a></li>
                    <li><a href="/about.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'class="active"' : ''; ?>>About</a></li>
                    <li><a href="/contact.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'class="active"' : ''; ?>>Contact</a></li>
                    <li>
                        <a href="/cart.php" class="cart-icon" <?php echo (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>>
                            Cart
                            <?php if ($cart_count > 0): ?>
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
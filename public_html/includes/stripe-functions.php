<?php
/**
 * GiggaDev E-commerce Platform
 * Stripe Payment Integration Functions
 */

// Include Stripe PHP SDK (requires the Composer autoloader)
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/config.php';

// Set your Stripe API key
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

/**
 * Create a Stripe Checkout Session for a product
 *
 * @param array $product Product data (id, name, price, description)
 * @param string $success_url URL to redirect on successful payment
 * @param string $cancel_url URL to redirect on canceled payment
 * @return \Stripe\Checkout\Session Stripe Checkout Session
 */
function create_checkout_session($product, $success_url, $cancel_url) {
    try {
        // Create a new Stripe Checkout Session
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product['name'],
                        'description' => $product['description'],
                    ],
                    'unit_amount' => $product['price'] * 100, // Stripe expects amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancel_url,
            'metadata' => [
                'product_id' => $product['id'],
            ],
        ]);
        
        return $checkout_session;
    } catch (\Exception $e) {
        error_log('Stripe error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Retrieve a Checkout Session by ID
 *
 * @param string $session_id Stripe Checkout Session ID
 * @return \Stripe\Checkout\Session|false Session object or false on error
 */
function get_checkout_session($session_id) {
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        return $session;
    } catch (\Exception $e) {
        error_log('Error retrieving Stripe session: ' . $e->getMessage());
        return false;
    }
}

/**
 * Handle Stripe webhook events
 *
 * @param string $payload The raw payload from Stripe
 * @param string $sig_header The Stripe signature header
 * @return bool True if the webhook was processed successfully
 */
function handle_stripe_webhook($payload, $sig_header) {
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, STRIPE_WEBHOOK_SECRET
        );
        
        // Handle different event types
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                process_successful_payment($session);
                break;
                
            case 'payment_intent.succeeded':
                $payment_intent = $event->data->object;
                // Log the successful payment intent
                error_log('Payment intent succeeded: ' . $payment_intent->id);
                break;
                
            case 'payment_intent.payment_failed':
                $payment_intent = $event->data->object;
                $error_message = $payment_intent->last_payment_error ? $payment_intent->last_payment_error->message : '';
                error_log('Payment failed: ' . $error_message);
                break;
                
            default:
                // Unexpected event type
                error_log('Received unknown event type: ' . $event->type);
        }
        
        return true;
    } catch (\UnexpectedValueException $e) {
        // Invalid payload
        error_log('Invalid payload: ' . $e->getMessage());
        return false;
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        error_log('Invalid signature: ' . $e->getMessage());
        return false;
    } catch (\Exception $e) {
        // Generic error
        error_log('Error processing webhook: ' . $e->getMessage());
        return false;
    }
}

/**
 * Process a successful payment
 *
 * @param \Stripe\Checkout\Session $session The Stripe Checkout Session
 * @return void
 */
function process_successful_payment($session) {
    // Get the product ID from the session metadata
    $product_id = $session->metadata->product_id;
    
    // Get customer information
    $customer_email = $session->customer_details->email;
    
    // Get payment information
    $payment_intent_id = $session->payment_intent;
    $amount_paid = $session->amount_total / 100; // Convert from cents
    
    // Log the successful payment
    error_log("Payment successful: Product ID: {$product_id}, Customer: {$customer_email}, Amount: {$amount_paid}");
    
    // Create an order in the database
    $order_id = create_order([
        'product_id' => $product_id,
        'customer_email' => $customer_email,
        'payment_id' => $payment_intent_id,
        'amount' => $amount_paid,
        'status' => 'completed',
    ]);
    
    // Generate a secure download link for digital products
    if ($order_id) {
        $download_token = generate_download_token($order_id, $product_id);
        // Send order confirmation email with download link
        send_order_confirmation_email($customer_email, $order_id, $download_token, $product_id);
    }
}

/**
 * Generate a secure download token for a product
 *
 * @param int $order_id Order ID
 * @param int $product_id Product ID
 * @return string Secure download token
 */
function generate_download_token($order_id, $product_id) {
    // Generate a random token
    $token = bin2hex(random_bytes(32));
    
    // Store the token in the database with an expiration time
    $expires_at = date('Y-m-d H:i:s', strtotime('+7 days'));
    
    // Insert into database
    global $db;
    $stmt = $db->prepare("INSERT INTO download_tokens (token, order_id, product_id, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$token, $order_id, $product_id, $expires_at]);
    
    return $token;
}

/**
 * Create a Stripe refund
 *
 * @param string $payment_intent_id Payment Intent ID to refund
 * @param int $amount Amount to refund in cents (optional, refunds entire amount if not specified)
 * @return \Stripe\Refund|false Refund object or false on error
 */
function create_refund($payment_intent_id, $amount = null) {
    try {
        $refund_params = [
            'payment_intent' => $payment_intent_id,
        ];
        
        // If amount is specified, add it to the parameters
        if ($amount !== null) {
            $refund_params['amount'] = $amount;
        }
        
        $refund = \Stripe\Refund::create($refund_params);
        return $refund;
    } catch (\Exception $e) {
        error_log('Error creating refund: ' . $e->getMessage());
        return false;
    }
}

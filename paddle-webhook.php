<?php
require 'config.php';

// Paddle webhook handler
$webhookData = json_decode(file_get_contents('php://input'), true);

// Verify webhook (Paddle signature verification)
function verifyPaddleWebhook($data, $signature) {
    // For development, skip verification - enable in production
    if (PADDLE_ENVIRONMENT === 'sandbox') {
        return true;
    }
    
    $public_key = file_get_contents('paddle_public_key.pem'); // Download from Paddle
    return openssl_verify($data, base64_decode($signature), $public_key, OPENSSL_ALGO_SHA1);
}

// Get signature from headers
$signature = $_SERVER['HTTP_X_PADDLE_SIGNATURE'] ?? '';

if (!verifyPaddleWebhook(file_get_contents('php://input'), $signature)) {
    http_response_code(401);
    exit('Unauthorized');
}

$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);

switch ($webhookData['alert_name']) {
    case 'subscription_payment_succeeded':
        handleSubscriptionPaymentSucceeded($webhookData, $db);
        break;
        
    case 'subscription_cancelled':
        handleSubscriptionCancelled($webhookData, $db);
        break;
        
    case 'subscription_payment_failed':
        handleSubscriptionPaymentFailed($webhookData, $db);
        break;
}

function handleSubscriptionPaymentSucceeded($data, $db) {
    $passthrough = json_decode($data['passthrough'], true);
    $user_id = $passthrough['user_id'];
    $plan = $passthrough['plan'];
    
    // Calculate expiry date
    $expires_at = $plan === 'yearly' ? 
        date('Y-m-d H:i:s', strtotime('+1 year')) : 
        date('Y-m-d H:i:s', strtotime('+1 month'));
    
    // Update user premium status
    $stmt = $db->prepare("UPDATE users SET is_premium = TRUE, premium_expires_at = ?, paddle_subscription_id = ? WHERE id = ?");
    $stmt->execute([$expires_at, $data['subscription_id'], $user_id]);
    
    // Log the transaction
    error_log("Premium activated for user {$user_id}, plan: {$plan}, expires: {$expires_at}");
}

function handleSubscriptionCancelled($data, $db) {
    $stmt = $db->prepare("UPDATE users SET is_premium = FALSE, premium_expires_at = NULL WHERE paddle_subscription_id = ?");
    $stmt->execute([$data['subscription_id']]);
    
    error_log("Premium cancelled for subscription: " . $data['subscription_id']);
}

function handleSubscriptionPaymentFailed($data, $db) {
    // Optionally handle failed payments - maybe send notification
    error_log("Payment failed for subscription: " . $data['subscription_id']);
}

http_response_code(200);
echo 'OK';
?>
<?php
require_once 'config.php'; // Include your Razorpay API keys
require_once 'vendor/autoload.php'; // Include Razorpay PHP SDK

use Razorpay\Api\Api;



$api = new Api($keyId, $keySecret);

// Capture the Razorpay payment details from the response
$razorpayPaymentId = $_POST['razorpay_payment_id'];
$razorpayOrderId = $_POST['razorpay_order_id'];
$razorpaySignature = $_POST['razorpay_signature'];

// Verify the signature
$attributes = [
    'razorpay_order_id' => $razorpayOrderId,
    'razorpay_payment_id' => $razorpayPaymentId,
    'razorpay_signature' => $razorpaySignature
];

try {
    $api->utility->verifyPaymentSignature($attributes);
    // Signature is correct, proceed with order processing
    echo "Payment verified successfully";
    // You can update your database and complete the order process here
} catch (Exception $e) {
    // Signature verification failed
    echo "Payment verification failed: " . $e->getMessage();
}
?>

<?php
require_once 'config.php'; // Include your Razorpay API keys
require_once 'vendor/autoload.php'; // Include Razorpay PHP SDK

use Razorpay\Api\Api;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$amount = $input['amount'] * 100; // Convert amount to paisa

$api = new Api($keyId, $keySecret);

// Create an order
$orderData = [
    'receipt'  => 'rcptid_' . uniqid(),
    'amount'   => $amount, // Amount in paisa
    'currency' => 'INR'
];

$razorpayOrder = $api->order->create($orderData);

$response = [
    'key' => $keyId,
    'amount' => $orderData['amount'],
    'order_id' => $razorpayOrder['id']
];

echo json_encode($response);
?>

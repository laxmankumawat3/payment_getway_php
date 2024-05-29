<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment Gateway Integration</title>
</head>
<body>
    <h2>Enter Payment Details</h2>
    <?php
    require_once 'config.php'; // Include your Razorpay API keys
    require_once 'vendor/autoload.php'; // Include Razorpay PHP SDK

    use Razorpay\Api\Api;


    $api = new Api($keyId, $keySecret);

    // Create an order
    $orderData = [
        'receipt'  => 'rcptid_' . uniqid(),
        'amount'   => 50000, // Amount in paisa (e.g., 50000 for ₹500.00)
        'currency' => 'INR'
    ];

    $razorpayOrder = $api->order->create($orderData);
    ?>
    <form id="paymentForm" action="pay.php" method="POST">
        <input type="hidden" name="razorpay_order_id" value="<?php echo $razorpayOrder->id; ?>">
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        <label for="amount">Amount (INR)</label><br>
        <input type="text" id="amount" name="amount" value="500"><br><br>
        <button type="submit">Pay Now</button>
    </form>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "<?php echo $keyId; ?>",
            "amount": document.getElementById('amount').value * 100, // Convert amount to paisa
            "currency": "INR",
            "name": "Acme Corp",
            "description": "Test Transaction",
            "image": "https://example.com/your_logo",
            "order_id": "<?php echo $razorpayOrder->id; ?>",
            "handler": function (response) {
                // Get the payment details
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                // Submit the form
                document.getElementById('paymentForm').submit();
            },
            "prefill": {
                "name": "Gaurav Kumar",
                "email": "gaurav.kumar@example.com",
                "contact": "9000090000"
            },
            "notes": {
                "address": "Razorpay Corporate Office"
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        document.getElementById('paymentForm').onsubmit = function(e){
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>

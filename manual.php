<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment Gateway Integration</title>
</head>
<body>
    <h2>Enter Payment Details</h2>
  
    <form id="paymentForm">
        <label for="amount">Amount (INR)</label><br>
        <input type="text" id="amount" name="amount"><br><br>
        <button type="button" id="payButton">Pay Now</button>
    </form>
    
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('payButton').onclick = function(e) {
            var amount = document.getElementById('amount').value;

            if (!amount) {
                alert('Please enter an amount.');
                return;
            }

            // Send the amount to the server to create the order
            fetch('create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ amount: amount })
            })
            .then(response => response.json())
            .then(data => {
                var options = {
                    "key": data.key, // Razorpay API Key
                    "amount": data.amount, // Amount in paisa
                    "currency": "INR",
                    "name": "Acme Corp",
                    "description": "Test Transaction",
                    "image": "https://example.com/your_logo",
                    "order_id": data.order_id, // Razorpay Order ID
                    "handler": function (response) {
                        // Get the payment details
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'pay.php';
                        form.innerHTML = `
                            <input type="hidden" name="razorpay_payment_id" value="${response.razorpay_payment_id}">
                            <input type="hidden" name="razorpay_order_id" value="${data.order_id}">
                            <input type="hidden" name="razorpay_signature" value="${response.razorpay_signature}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
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
                rzp1.open();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

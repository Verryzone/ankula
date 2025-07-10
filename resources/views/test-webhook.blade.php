<!DOCTYPE html>
<html>
<head>
    <title>Test Midtrans Webhook</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 10px 0; }
        label { display: block; margin-bottom: 5px; }
        input, select { padding: 8px; width: 300px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9; }
    </style>
</head>
<body>
    <h1>Test Midtrans Webhook Notification</h1>
    
    <form id="webhookForm">
        <div class="form-group">
            <label>Order ID:</label>
            <input type="text" name="order_id" value="INV2025070001" required>
        </div>
        
        <div class="form-group">
            <label>Transaction Status:</label>
            <select name="transaction_status" required>
                <option value="settlement">Settlement (Paid)</option>
                <option value="capture">Capture (Paid)</option>
                <option value="pending">Pending</option>
                <option value="deny">Deny</option>
                <option value="cancel">Cancel</option>
                <option value="expire">Expire</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Fraud Status:</label>
            <select name="fraud_status">
                <option value="accept">Accept</option>
                <option value="challenge">Challenge</option>
                <option value="deny">Deny</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Payment Type:</label>
            <input type="text" name="payment_type" value="bank_transfer">
        </div>
        
        <div class="form-group">
            <label>Transaction Time:</label>
            <input type="text" name="transaction_time" value="{{ date('Y-m-d H:i:s') }}">
        </div>
        
        <button type="submit">Send Webhook Notification</button>
    </form>
    
    <div id="result" class="result" style="display: none;"></div>
    
    <script>
        document.getElementById('webhookForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            fetch('/payment/notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').style.display = 'block';
                document.getElementById('result').innerHTML = '<h3>Response:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('result').style.display = 'block';
                document.getElementById('result').innerHTML = '<h3>Error:</h3><pre>' + error + '</pre>';
            });
        });
    </script>
</body>
</html>

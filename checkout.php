<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>
</head>
<body>
    <h2>Checkout</h2>
    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        fetch('charge.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                window.snap.pay(data.token);
            });
    };
    </script>
</body>
</html>

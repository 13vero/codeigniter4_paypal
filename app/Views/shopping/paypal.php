<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.paypal.com/sdk/js?client-id=AeImMgOGFxyy7EWxwjIJ1SR6W1WgVSA33Ix3RIIHYCqkR5wYKDb2JUrRZL7OXVHBu8zwd3IQYDBKkSH1"></script>
    <title>PayPal</title>
</head>
<body>
    <h1>Pagos con PayPal</h1>
    <div id="paypalCard">

    </div>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        description: "Super Product",
                        amount: {
                            value: '0.01',
                        }
                    }],
                    application_context: {
                        shipping_preference: 'NO_SHIPPING'
                    }
                });
            },
            onApprove: function(data, actions) {
                console.log(data);
                fetch('/paypal/proccess/'+ data.orderID, {
                    method: 'POST'
                }).then(res => res.json())
                .then(res => {
                    console.log(res);
                    alert(res.msj);
                })
            }
        }).render('#paypalCard');
    </script>
</body>
</html>
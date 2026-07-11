<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .invoice {
            background-color: #fff;
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .invoice-body {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .invoice-body p {
            margin: 5px 0;
        }

        .invoice-footer {
            text-align: right;
        }
    </style>

</head>

<body>

    <div class="invoice">
        <div class="invoice-header">
            <h2>Invoice #12345</h2>
            <p>Date: 17th February 2025</p>
        </div>
        <div class="invoice-body">
            <p><strong>Bill To:</strong> John Doe</p>
            <p>123 Main St, Anytown, USA</p>
            <p>Email: johndoe@example.com</p>
        </div>
        <table width="100%">
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Item 1</td>
                <td>2</td>
                <td>$50.00</td>
                <td>$100.00</td>
            </tr>
            <tr>
                <td>Item 2</td>
                <td>1</td>
                <td>$80.00</td>
                <td>$80.00</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Subtotal:</td>
                <td>$180.00</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;">Tax (10%):</td>
                <td>$18.00</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>$198.00</strong></td>
            </tr>
        </table>
        <div class="invoice-footer">
            <p>Payment due by: 1st March 2025</p>
        </div>
    </div>

</body>

</html>
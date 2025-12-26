<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .product-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .product-info h3 {
            margin-top: 0;
            color: #dc3545;
        }

        .stock-quantity {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Low Stock Alert</h1>
    </div>

    <div class="alert">
        <strong>A product in your inventory is running low on stock.</strong>
    </div>

    <div class="product-info">
        <h3>{{ $product->name }}</h3>
        <p><strong>Product ID:</strong> #{{ $product->id }}</p>
        <p><strong>Current Stock:</strong> <span class="stock-quantity">{{ $product->stock_quantity }}</span> units</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    </div>
</body>

</html>
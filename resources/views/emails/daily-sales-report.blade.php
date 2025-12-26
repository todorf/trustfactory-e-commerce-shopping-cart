<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary {
            background-color: #e7f3ff;
            border: 1px solid #0066cc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary h2 {
            margin-top: 0;
            color: #0066cc;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 16px;
        }

        .summary-value {
            font-weight: bold;
            color: #0066cc;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        .products-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-weight: bold;
        }

        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .products-table tr:hover {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .no-sales {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Daily Sales Report</h1>
        <p><strong>Date:</strong> {{ $reportDate->format('F j, Y') }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <div class="summary-item">
            <span>Total Checkouts:</span>
            <span class="summary-value">{{ number_format($totalCheckouts) }}</span>
        </div>
        <div class="summary-item">
            <span>Total Revenue:</span>
            <span class="summary-value">${{ number_format($totalPrice, 2) }}</span>
        </div>
        <div class="summary-item">
            <span>Products Sold:</span>
            <span class="summary-value">{{ count($productsSold) }} products </span>
        </div>
    </div>

    @if (count($productsSold) > 0)

    <h2>Products Sold</h2>
    <table class="products-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productsSold as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td class="text-right">{{ number_format($product['quantity']) }}</td>
                <td class="text-right">${{ number_format($product['total_price'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-sales">
        <p><strong>No products were sold on this date.</strong></p>
    </div>
    @endif
</body>

</html>
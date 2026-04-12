<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Pendapatan</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lexend', sans-serif;
            /* background-color: #1e1e2c; */
            color: #1e1e2c;;
            margin: 0;
            padding: 20px;
        }

        .container {
            /* background-color: #1e1e2c; */
            padding: 20px;
            border: 2px solid #1e1e2c;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #1e1e2c;;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #1e1e2c;;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #1e1e2c;;
            color: #f29f67;
        }

        .totals {
            font-weight: bold;
            text-align: right;
            background-color: #1e1e2c;;
            color: #f29f67;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Akun Pendapatan</h1>
        <h3>Waktu Periode {{ $date }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Penjualan</td>
                    <td>{{ number_format($total_sales, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Pendapatan Penjualan</td>
                    <td>{{ number_format($total_sales_payments, 2) }}</td>
                </tr>
                <tr>
                    @php
                        $outstanding_receivables = $total_sales_payments - $total_sales;
                    @endphp
                    <td>Piutang Usaha</td>
                    <td>{{ number_format($outstanding_receivables, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Belanja</td>
                    <td>{{ number_format($total_purchases, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Pelunasan Belanja</td>
                    <td>{{ number_format($total_purchase_payments, 2) }}</td>
                </tr>
                <tr>
                    @php
                        $outstanding_payables = $total_purchase_payments - $total_purchases;
                    @endphp
                    <td>Utang Usaha</td>
                    <td>{{ number_format($outstanding_payables, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals">Net Cash Flow</td>
                    <td class="totals">{{ number_format($outstanding_receivables - $outstanding_payables, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>

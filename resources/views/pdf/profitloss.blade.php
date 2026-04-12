<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #1e1e2c;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid #1e1e2c;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #1e1e2c;
            color: #f29f67;
        }

        .totals {
            font-weight: bold;
            text-align: right;
            background-color: #1e1e2c;
            color: #f29f67;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Laba Rugi</h1>
            <p>Untuk Periode: {{ $date }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Stok Awal</td>
                    <td>{{ number_format($opening_stock, 2) }}</td>
                </tr>
                <tr>
                    <td>Penjualan</td>
                    <td>{{ number_format($total_purchases, 2) }}</td>
                </tr>
                <tr>
                    <td>Stok Akhir</td>
                    <td>{{ number_format($closing_stock, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Harga Pokok Penjualan (HPP)</strong></td>
                    <td>{{ number_format($opening_stock + $total_purchases - $closing_stock, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Pendapatan</td>
                    <td>{{ number_format($total_revenue, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Laba Kotor</strong> (Sales Revenue - COGS)</td>
                    <td>{{ number_format($total_revenue - ($opening_stock + $total_purchases - $closing_stock), 2) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                @php
                    $net_profit = $total_revenue - ($opening_stock + $total_purchases - $closing_stock);
                @endphp
                <tr>
                    <td class="totals">Laba Bersih</td>
                    <td class="totals">{{ number_format($net_profit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Lexend", sans-serif;
            /* font-optical-sizing: auto; */
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
            font-size: 14px;
        }

        .container {
            width: 95%;
            height: 95%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #c37800;
            border-radius: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header img {
            margin: 0;
            margin-bottom: 15px;
            width: 60px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .details {
            display: flex;
            width: 100% justify-content: space-between;
            margin-bottom: 30px;
        }

        .details div {
            width: 45%;
            margin-bottom: 40px
        }

        .details h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #555;
        }

        .details p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background: #f4f4f4;
            font-weight: bold;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('company_logo.png') }}" alt="Company Logo">
            <h1>Purchase Order</h1>
            <p><strong>Tanggal Order:</strong> {{ Carbon\Carbon::parse($order->order_date)->format('D jS F, Y') }}</p>
            <p><strong>Nomor Order:</strong> #{{ sprintf('%04d', $order->id) }}</p>
        </div>

        <div class="details">
            <div>
                <h3>Detail Supplier</h3>
                <p><strong>Nama:</strong> {{ $order->supplier->name }}</p>
                <p><strong>Alamat:</strong> {{ $order->supplier->address }}</p>
                <p><strong>Email:</strong> {{ $order->supplier->email }}</p>
                <p><strong>No HP:</strong> {{ $order->supplier->phone_number }}</p>
            </div>

            <div style="margin-left:auto">
                <h3>Detail Pembeli</h3>
                <p><strong>Nama:</strong> {{ env('COMPANY_NAME') }}</p>
                <p><strong>Alamat:</strong> {{ env('COMPANY_ADDRESS') }}</p>
                <p><strong>Email:</strong> {{ env('COMPANY_EMAIL') }}</p>
                <p><strong>No HP:</strong> {{ env('COMPANY_PHONE') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Ukuran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $key => $product)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <h3><strong>{{ $product->name }}</strong></h3>
                        </td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ $product->unit->name }}</td>
                        <td>{{ $product->size->name ?? '-' }}</td>
                        <td>{{ $product->pivot->notes ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->notes)
        <div style="margin-bottom: 30px;">
            <h4>Catatan:</h4>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Mohon dibuatkan invoice atas pesanan pembelian kami di atas. Demikian kami sampaikan.</p>
            <p>Atas perhatian dan kerjasamanya kami sampaikan terima kasih.</p>
            <br>
            <p>Dony Sudaryanto</p>
            <p>Direktur</p>
        </div>
    </div>
</body>

</html>

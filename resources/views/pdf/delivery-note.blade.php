<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan</title>
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
            <h1>Surat Jalan</h1>
            <p><strong>Tanggal:</strong> {{ Carbon\Carbon::parse($deliveryNote->created_at)->format('D jS F, Y') }}</p>
            <p><strong>Nomor Surat Jalan:</strong> #{{ sprintf('%04d', $deliveryNote->id) }}</p>
        </div>

        <div class="details">
            <div>
                <h3>Detail Driver</h3>
                <p><strong>Nama Driver:</strong> {{ $deliveryNote->driver_name }}</p>
                <p><strong>Nomor Kendaraan:</strong> {{ $deliveryNote->vehicle_number }}</p>
            </div>

            <div style="margin-left:auto">
                <h3>Detail Penerima</h3>
                <p><strong>Nama Penerima:</strong> {{ $deliveryNote->receiver_name }}</p>
                <p><strong>Klien:</strong> {{ $deliveryNote->sale->client->name }}</p>
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
                @foreach ($deliveryNote->sale->products as $key => $product)
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

        <div class="footer">
            <p>Surat jalan ini dibuat untuk pengiriman barang sesuai pesanan.</p>
            <p>Demikian kami sampaikan.</p>
            <br>
            <p>Dony Sudaryanto</p>
            <p>Direktur</p>
        </div>
    </div>
</body>

</html>

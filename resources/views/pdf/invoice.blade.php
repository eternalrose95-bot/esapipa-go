<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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
            <h1>Invoice</h1>
            <p><strong>Tanggal Invoice:</strong> {{ Carbon\Carbon::parse($invoice->invoice_date)->format('D jS F, Y') }}</p>
            <p><strong>Nomor Invoice:</strong> #{{ sprintf('%04d', $invoice->id) }}</p>
        </div>

        <div class="details">
            <div>
                <h3>Detail Klien</h3>
                <p><strong>Nama:</strong> {{ $invoice->client->name }}</p>
                <p><strong>Alamat:</strong> {{ $invoice->client->address }}</p>
                <p><strong>Email:</strong> {{ $invoice->client->email }}</p>
                <p><strong>No HP:</strong> {{ $invoice->client->phone_number }}</p>
            </div>

            <div style="margin-left:auto">
                <h3>Detail Penjual</h3>
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
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan<p style="font-size:10px;font:bold">Rp</p>
                    </th>
                    <th>Diskon (%)</th>
                    <th>Total<p style="font-size:10px;font:bold">Rp</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->products as $key => $product)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <h3><strong>{{ $product->name }}</strong></h3>
                        </td>
                        <td>{{ $product->pivot->quantity }} {{ $product->unit?->name ?? '' }}</td>
                        <td>
                            @if($product->pivot->discount_percentage > 0)
                                <s>{{ number_format($product->pivot->unit_price, 0, ',', '.') }}</s><br>
                                {{ number_format($product->pivot->unit_price * (1 - $product->pivot->discount_percentage / 100), 0, ',', '.') }}
                            @else
                                {{ number_format($product->pivot->unit_price, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $product->pivot->discount_percentage ?? 0 }}</td>
                        <td>Rp {{ number_format(($product->pivot->unit_price * (1 - ($product->pivot->discount_percentage ?? 0) / 100)) * $product->pivot->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align:right"><strong>Subtotal</strong></td>
                    <td><strong>Rp {{ number_format(round($invoice->subtotal ?? 0), 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:right"><strong>PPN ({{ number_format($invoice->tax_percentage ?? 0, 0) }}%)</strong></td>
                    <td><strong>Rp {{ number_format(round($invoice->tax_amount ?? 0), 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5" style="text-align:right"><strong>Grand Total</strong></td>
                    <td><strong>Rp {{ number_format(round($invoice->grand_total ?? $invoice->total_amount ?? 0), 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This document was generated electronically and does not require a signature.</p>
        </div>
    </div>
</body>

</html>

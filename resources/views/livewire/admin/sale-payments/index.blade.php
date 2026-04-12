<div>
    <x-slot:header>Daftar Penjualan Lunas</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Penjualan</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal dan Waktu</th>
                        <th>Klien</th>
                        <th>Referensi</th>
                        <th>Total Penjualan</th>
                        <th>Total Dibayar</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales_payments as $payment)
                        <tr>
                            <td scope="row">{{ $payment->id }}</td>
                            <td>
                                <h6>{{ Carbon\Carbon::parse($payment->payment_time)->format('jS F,Y h:i:sA') }}</h6>
                            </td>
                            <td>
                                <h5>{{ $payment->client->name }}</h5>
                                <h6>Balance: <strong>Rp {{ number_format($payment->client->balance, 0, ',', '.') }}</strong>
                                </h6>
                            </td>
                            <td>
                                <small>{{ $payment->transaction_reference }}</small>
                            </td>

                            <td>
                                @foreach ($payment->sales as $sale)
                                    <li>
                                        Sale No: #{{ $sale->id }} <br>
                                        Rp {{ number_format($sale->total_paid, 0, ',', '.') }}
                                    </li>
                                @endforeach
                            </td>
                            <td>
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                <a wire:navigate href="{{ route('admin.sale-payments.edit', $payment->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Sale Payment?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $payment->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>TOTALS</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong>
                                Rp
                                {{ number_format(
                                    $sales_payments->sum(function ($payment) {
                                        return $payment->sales->sum(function ($sale) {
                                            return $sale->pivot->amount;
                                        });
                                    }),
                                    2,
                                ) }}

                            </strong></td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $sales_payments->sum(function ($payment) {
                                        return $payment->amount;
                                    }),
                                    2,
                                ) }}
                            </strong>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

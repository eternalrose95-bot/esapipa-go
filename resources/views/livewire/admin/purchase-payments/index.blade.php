<div>
    <x-slot:header>Pelunasan Pembelian</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Pembayaran Lunas</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal dan Waktu</th>
                        <th>Supplier</th>
                        <th>Nomor PO</th>
                        <th>Faktur Pembelian</th>
                        <th>Total Yang Dibayar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase_payments as $payment)
                        <tr>
                            <td scope="row">{{ $payment->id }}</td>
                            <td>
                                <h6>{{ Carbon\Carbon::parse($payment->payment_time)->format('jS F,Y h:i:sA') }}</h6>
                            </td>
                            <td>
                                <h6>{{ $payment->supplier->name }}</h6>
                            </td>
                            <td>
                                <small>{{ $payment->transaction_reference }}</small>
                            </td>

                            <td>
                                @foreach ($payment->purchases as $purchase)
                                    <li>
                                        Rp {{ number_format($purchase->total_paid, 0, ',', '.') }}
                                    </li>
                                @endforeach
                            </td>
                            <td>
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                {{-- <a wire:navigate href="{{ route('admin.purchase-payments.edit', $payment->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a> --}}
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Purchase Payment?')||event.stopImmediatePropagation()"
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
                                    $purchase_payments->sum(function ($payment) {
                                        return $payment->purchases->sum(function ($purchase) {
                                            return $purchase->pivot->amount;
                                        });
                                    }),
                                    2,
                                ) }}

                            </strong></td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $purchase_payments->sum(function ($payment) {
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

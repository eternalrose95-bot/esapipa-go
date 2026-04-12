<div>
    <x-slot:header>Permintaan</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.orders.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Permintaan
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Order</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>Tanggal Order</th>
                        <th>Nama Supplier</th>
                        <th>Detail Produk</th>
                        <th>Keterangan</th>
                        <th>Total Belanja</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                <h6>{{ Carbon\Carbon::parse($order->order_date)->format('jS F,Y') }}</h6>
                            </td>
                            <td>
                                {{ $order->supplier->name }}
                            </td>
                            <td>
                                @if($order->products->count() > 0)
                                    @foreach($order->products as $product)
                                        <small>
                                            {{ number_format($product->pivot->quantity) }}x {{ $product->name }}
                                        </small>
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                @else
                                    <small>-</small>
                                @endif
                            </td>
                            <td>
                                @if($order->products->where('pivot.notes', '!=', '')->count())
                                    @foreach($order->products as $product)
                                        @if($product->pivot->notes)
                                            <small>
                                                <em>{{ $product->pivot->notes }}</em>
                                            </small>
                                            @if(!$loop->last)<br>@endif
                                        @endif
                                    @endforeach
                                @else
                                    <small>-</small>
                                @endif
                            </td>
                            <td>
                                <small>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</small>
                            </td>
                            {{-- <td>
                                <span class="{{ $order->is_paid ? 'text-success' : 'text-danger' }}"
                                    style="font: bold">
                                    {{ $order->is_paid ? 'Paid' : 'Not Paid' }}
                                </span>
                            </td> --}}
                            <td class="text-center">
                                <a href="{{ route('admin.orders.edit', $order->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a target="_blank" href="{{ route('admin.order-download', $order->id) }}" class="btn btn-primary">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Order?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $order->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div>
    <x-slot:header>Permintaan</x-slot:header>

    <div class="row justify-content-center">
        <div class="col-md-4 col-6 @if (!$productList) w-50 @endif">

            <div class="card">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Atur Tanggal & Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Tanggal Order</label>
                        <input wire:model='order.order_date' type="date" class="form-control" />
                        @error('order.order_date')
                            <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Cari Supplier</label>
                        <input type="text" wire:model.live='supplierSearch' class="form-control" />
                        @error('order.supplier_id')
                            <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        <ul class="list-group mt-2 w-100">
                            @if ($supplierSearch != '')
                                @foreach ($suppliers as $supplier)
                                    <li wire:click='selectSupplier({{ $supplier->id }})'
                                        class="list-group-item {{ $supplier->id == $order->supplier_id ? 'active' : '' }}">
                                        {{ $supplier->name }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>


                </div>
            </div>
            <div class="card mt-2">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Tambahkan Produk</h5>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label for="" class="form-label">Cari Produk</label>
                        <input type="text" wire:model.live='productSearch' class="form-control" />
                        <ul class="list-group mt-2 w-100">
                            @if ($productSearch != '')
                                @foreach ($products as $product)
                                    <x-product-list-group :product="$product" :selectedProductId="$selectedProductId"/>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Jumlah</label>
                                <input wire:model='quantity' type="number" min="0" class="form-control" />
                                @error('quantity')
                                    <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Keterangan (Opsional)</label>
                                <textarea wire:model="notes" class="form-control" rows="2" placeholder="Tambahkan keterangan untuk produk ini..."></textarea>
                            </div>
                        </div>
                    </div>

                    <button
                        onclick="confirm('Are you sure you wish to add this Product to the list')||event.stopImmediatePropagation()"
                        wire:click='addToList' class="btn btn-dark text-inv-primary">Tambahkan</button>
                </div>
            </div>
        </div>
        @if ($productList)
            <div class="col-md-8 col-6">
                <div class="card shadow">
                    <div class="card-header bg-inv-primary text-inv-secondary border-0">
                        <h5 class="text-center text-uppercase">Keranjang</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Ukuran</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($productList)
                                    @php
                                        $total = 0;
                                    @endphp
                                    @foreach ($productList as $key => $listItem)
                                        <tr>
                                            <td scope="row">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ App\Models\Product::find($listItem['product_id'])->name }} <br>
                                                <small
                                                    class="text-muted">{{ App\Models\Product::find($listItem['product_id'])->quantity . App\Models\Product::find($listItem['product_id'])->unit->name }}</small>
                                            </td>
                                            <td>{{ $listItem['quantity'] }}</td>
                                            <td>{{ App\Models\Product::find($listItem['product_id'])->unit->name }}</td>
                                            <td>{{ App\Models\Product::find($listItem['product_id'])->size->name ?? '-' }}</td>
                                            <td>
                                                <small>{{ $listItem['notes'] ?: '-' }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if ($listItem['quantity'] > 1)
                                                    <button wire:click='subtractQuantity({{ $key }})'
                                                        class="btn btn-warning">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                @endif
                                                <button wire:click='addQuantity({{ $key }})'
                                                    class="btn btn-success">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                                <button
                                                    onclick="confirm('Are you sure you wish to remove this item from the list')||event.stopImmediatePropagation()"
                                                    wire:click='deleteCartItem({{ $key }})'
                                                    class="btn btn-danger">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        @php
                                            $total += $listItem['quantity'] * $listItem['price'];
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class="mb-3">
                            <label class="form-label">Catatan Order (Opsional)</label>
                            <textarea wire:model="order.notes" class="form-control" rows="3" placeholder="Tambahkan catatan untuk order ini..."></textarea>
                        </div>

                            <button
                                onclick="confirm('Are you sure you wish to make the order')||event.stopImmediatePropagation()"
                                wire:click='makeOrder' class="btn btn-dark text-inv-primary w-100">Make Order</button>

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div>
    <x-slot:header>Penjualan</x-slot:header>

    <div class="row justify-content-center">
        <div class="col-md-4 col-6 @if (!$productList) w-50 @endif">

            <div class="card">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Atur Tanggal Dan Klien</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="" class="form-label">Tanggal Penjualan</label>
                        <input wire:model='sale.sale_date' type="date" class="form-control" />
                        @error('sale.sale_date')
                            <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Cari Klien</label>
                        <input type="text" wire:model.live='clientSearch' class="form-control" />
                        @error('sale.client_id')
                            <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                        <ul class="list-group mt-2 w-100">
                            @if ($clientSearch != '')
                                @foreach ($clients as $client)
                                    <x-client-list-item :client="$client" :sale="$sale"/>
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
                                <label for="" class="form-label">Harga Satuan</label>
                                <input wire:model='price' type="number" min="0" class="form-control"/>
                                @error('price')
                                    <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Diskon (%)</label>
                                <input wire:model="discount" type="number" min="0" class="form-control" />
                            </div>
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
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Diskon</th>
                                    <th>Harga Total</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Actions</th>
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
                                                {{ App\Models\Product::find($listItem['product_id'])->id }}
                                            </td>
                                            <td>
                                                {{ App\Models\Product::find($listItem['product_id'])->name }} <br>
                                                <small
                                                    class="text-muted">{{ App\Models\Product::find($listItem['product_id'])->quantity . App\Models\Product::find($listItem['product_id'])->unit->name }}</small>
                                            </td>
                                            <td>{{ $listItem['quantity'] }}</td>
                                            <td>
                                                @if (($listItem['discount'] ?? 0) > 0)
                                                    <span style="text-decoration: line-through; color: gray;">
                                                        Rp {{ number_format($listItem['original_price'], 0, ',', '.') }}
                                                    </span>
                                                    <br>
                                                @endif
                                                Rp {{ number_format($listItem['price'], 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input wire:model.live="productList.{{ $key }}.discount"
                                                        type="number"
                                                        min="0"
                                                        class="form-control"
                                                        style="max-width: 80px;"
                                                        value="{{ $listItem['discount'] ?? 0 }}" />
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($listItem['quantity'] * $listItem['price'], 0, ',', '.') }}
                                            </td>
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
                                    <tr>
                                        <td colspan="4"><strong>SUBTOTAL</strong></td>
                                        <td colspan="3">
                                            <strong>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <strong>PPN %</strong>
                                        </td>
                                        <td colspan="2">
                                            <input wire:model.live="tax"
                                                    type="number"
                                                    min="0"
                                                    max="100"
                                                    class="form-control w-50"/>
                                        </td>
                                        <td colspan="3">
                                            <strong>Rp {{ number_format($this->taxAmount, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-size: 18px">
                                            <strong>TOTAL AKHIR</strong>
                                        </td>
                                        <td colspan="3" style="font-size: 18px">
                                            <strong>Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                            <button
                                onclick="confirm('Are you sure you wish to Update this sale')||event.stopImmediatePropagation()"
                                wire:click='makeSale' class="btn btn-dark text-inv-primary w-100">Update</button>

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

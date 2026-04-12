<div>
    <x-slot:header>Quotations</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.quotations.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Penawaran
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Penawaran</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>Tanggal Penawaran</th>
                        <th>Klien</th>
                        <th>Detail Produk</th>
                        <th>Keterangan</th>
                        <th>Total Harga</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotations as $quotation)
                        <tr>
                            <td>
                                <h6>{{ Carbon\Carbon::parse($quotation->quotation_date)->format('jS F,Y') }}</h6>
                            </td>
                            <td>
                                {{ $quotation->client_name }}
                            </td>
                            <td>
                                @if($quotation->products->count() > 0)
                                    @foreach($quotation->products as $product)
                                        <small>
                                            {{ number_format($product->pivot->quantity) }}x {{ $product->name ?? 'Produk tidak tersedia' }}
                                        </small>
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                @else
                                    <small>-</small>
                                @endif
                            </td>
                            <td>
                                @if($quotation->products->where('pivot.notes', '!=', '')->count())
                                    @foreach($quotation->products as $product)
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
                                <small>Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</small>
                            </td>
                            {{-- <td>
                                <span class="{{ $quotation->is_paid ? 'text-success' : 'text-danger' }}"
                                    style="font: bold">
                                    {{ $quotation->is_paid ? 'Paid' : 'Not Paid' }}
                                </span>
                            </td> --}}
                            <td class="text-center">
                                <a href="{{ route('admin.quotations.edit', $quotation->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-success" wire:click="convertToSale({{ $quotation->id }})">
                                    <i class="bi bi-arrow-right-circle"></i>
                                </button>
                                <a target="_blank" href="{{ route('admin.quotation-download', $quotation->id) }}" class="btn btn-primary">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Quotation?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $quotation->id }})'>
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

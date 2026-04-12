<div>
    <x-slot:header>Supplier</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Supplier
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Supplier</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Detail Utama</th>
                        <th>Alamat</th>
                        <th>Detail</th>
                        <th>Detail Rekening</th>
                        <th>Total Pembelian</th>
                        <th>Total Harga Pembelian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>
                                <h6>{{ $supplier->name }}</h6>
                                <small>{{ $supplier->email }}</small><br>
                                <small>{{ $supplier->phone_number }}</small>
                            </td>
                            <td>{{ $supplier->address }}</td>
                            <td>
                                <small><strong>Tax ID:</strong> {{ $supplier->tax_id ?? 'N/A' }}</small><br>
                            </td>
                            <td>
                                <small><strong>Bank:</strong> {{ $supplier->bank->name ?? 'N/A' }}</small><br>
                                <small><strong>A/c No:</strong> {{ $supplier->account_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                {{ $supplier->purchases->count() }}
                            </td>
                            <td>
                                <small>Rp
                                </small>{{ number_format(
                                    $supplier->purchases->sum(function ($sale) {
                                        return $sale->total_amount;
                                    }),
                                ) }}
                            </td>
                            <td class="text-center">
                                <a wire:navigate href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to DELETE this Supplier?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $supplier->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>{{ $suppliers->links() }}</tr>
                </tfoot>
            </table>
            {{ $suppliers->links() }}
        </div>
    </div>
</div>

<div>
    <x-slot:header>Klien</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.clients.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Klien
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Klien Tersedia</h5>
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
                        <th>Jumlah Pembelian</th>
                        <th>Jumlah Total Pembelian</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>
                                <h6>{{ $client->name }}</h6>
                                <small>{{ $client->email }}</small><br>
                                <small>{{ $client->phone_number }}</small>
                            </td>
                            <td>{{ $client->address }}</td>
                            <td>
                                <small><strong>Tax ID:</strong> {{ $client->tax_id ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small><strong>Bank:</strong> {{ $client->bank->name ?? 'N/A' }}</small><br>
                                <small><strong>A/c No:</strong> {{ $client->account_number ?? 'N/A' }}</small>
                            </td>
                            <td>
                                {{ $client->sales->count() }}
                            </td>
                            <td>
                                <small>Rp </small>{{ number_format($client->sales->sum(function ($sale) {
                                    return $sale->total_amount;
                                })) }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to DELETE this client?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $client->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        {{ $clients->links() }}
                    </tr>
                </tfoot>
            </table>
            {{ $clients->links() }}
        </div>
    </div>
</div>

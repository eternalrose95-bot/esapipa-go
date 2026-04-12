<div>
    <x-slot:header>Belanja Operasional</x-slot:header>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.operational-expenses.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Belanja Operasional
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5 class="mb-0">Daftar Belanja Operasional</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Nama Toko</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($operationalExpenses as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expense->invoice_number }}</td>
                            <td>{{ $expense->date->format('d-m-Y') }}</td>
                            <td>{{ $expense->store_name }}</td>
                            <td>{{ $expense->products_summary }}</td>
                            <td>Rp {{ number_format($expense->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $expense->is_paid ? 'success' : 'warning' }}">
                                    {{ $expense->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.operational-expenses.edit', $expense->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Yakin ingin menghapus belanja operasional ini?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger btn-sm" wire:click="delete({{ $expense->id }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada belanja operasional.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div>
    <x-slot:header>Satuan Produk</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.units.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Satuan
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Satuan Produk</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Singkatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->symbol }}</td>

                            <td class="text-center">
                                <a href="{{ route('admin.units.edit', $unit->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Are you sure you wish to DELETE this Unit?')||event.stopImmediatePropagation()" class="btn btn-danger" wire:click='delete({{ $unit->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>{{ $units->links() }}</tr>
                </tfoot>
            </table>
            {{ $units->links() }}
        </div>
    </div>
</div>

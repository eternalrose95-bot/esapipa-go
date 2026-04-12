<div>
    <x-slot:header>Ukuran</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.sizes.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Ukuran
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Ukuran</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sizes as $size)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $size->name }}</td>

                            <td class="text-center">
                                <a href="{{ route('admin.sizes.edit', $size->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Are you sure you wish to DELETE this Size?')||event.stopImmediatePropagation()" class="btn btn-danger" wire:click='delete({{ $size->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>{{ $sizes->links() }}</tr>
                </tfoot>
            </table>
            {{ $sizes->links() }}
        </div>
    </div>
</div>

<div>
    <x-slot:header>Merk</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.brands.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Merk
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Merk Tersedia</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($brands as $brand)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->distinct_categories->pluck('name')->join(', ') }}</td>

                            <td class="text-center">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Are you sure you wish to DELETE this brand?')||event.stopImmediatePropagation()" class="btn btn-danger" wire:click='delete({{ $brand->id }})'>
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

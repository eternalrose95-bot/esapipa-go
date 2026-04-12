<div>
    <x-slot:header>Kategori Produk</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.product-categories.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Kategori</h5>
        </div>
        <div class="card-body table-responsive">
            @if ($showProducts && $selectedCategoryId)
                <div class="mb-4 p-3 border rounded bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1">Produk dalam kategori: {{ $selectedCategoryName }}</h6>
                            <small>{{ count($selectedCategoryProducts) }} produk ditemukan.</small>
                        </div>
                        <button class="btn btn-sm btn-secondary" wire:click="hideProducts">Tutup</button>
                    </div>
                    @if (empty($selectedCategoryProducts))
                        <div class="text-muted">Tidak ada produk dalam kategori ini.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($selectedCategoryProducts as $product)
                                <li class="list-group-item">
                                    <strong>{{ $product['name'] }}</strong>
                                    <div class="text-muted small">{{ $product['description'] ?? 'Tanpa deskripsi' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jumlah Produk</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productCategories as $category)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('admin.product-categories.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                                    {{ $category->products_count }}
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.product-categories.edit', $category->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Are you sure you wish to DELETE this category?')||event.stopImmediatePropagation()" class="btn btn-danger" wire:click='delete({{ $category->id }})'>
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

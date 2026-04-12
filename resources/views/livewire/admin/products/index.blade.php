<div>
    <x-slot:header>Produk</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
        <button type="button" class="btn btn-success text-white ms-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload"></i> Impor Produk dari Excel
        </button>
        <button type="button" class="btn btn-info text-white ms-2" wire:click="exportProducts">
            <i class="bi bi-download"></i> Ekspor Produk ke Excel
        </button>
    </div>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label for="search" class="form-label">Cari Produk</label>
                    <input wire:model.live='search' type="text" class="form-control" name="search" id="search"
                        placeholder="Cari berdasarkan nama atau SKU..." />
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label for="category" class="form-label">Filter Kategori</label>
                    <select wire:model.live='category_id' class="form-select" name="category" id="category">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Produk</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Detail Produk</th>
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Ukuran</th>
                        <th>Total Stok</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td scope="row">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-info">{{ $product->sku ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <h6>{{ $product->name }}</h6>
                                <small>{{ $product->description ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if ($product->image)
                                    <a href="{{ asset('storage/' . $product->image) }}" target="_blank" title="Lihat Gambar">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                            style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                {{ $product->unit->name }}
                            </td>
                            <td>
                                {{ $product->size->name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $product->inventory_balance }}
                            </td>
                            <td class="text-center">
                                <a wire:navigate href="{{ route('admin.products.edit', $product->id) }}"
                                    class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Are you sure you wish to DELETE this product?')||event.stopImmediatePropagation()" class="btn btn-danger btn-sm" wire:click='delete({{ $product->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>{{ $products->links() }}</tr>
                </tfoot>
            </table>
            {{ $products->links() }}
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Impor Produk dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="importProducts">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Pilih File Excel</label>
                            <input type="file" wire:model="excelFile" class="form-control" id="excelFile" accept=".xlsx,.xls">
                            @error('excelFile') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Impor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

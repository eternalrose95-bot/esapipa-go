<div>
    <form wire:submit.prevent="save">
        <x-slot:header>Tambah Belanja Operasional</x-slot:header>

        <div class="card mb-4">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5 class="mb-0">Informasi Tagihan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label class="form-label">Nomor Invoice</label>
                        <input type="text" class="form-control" value="{{ $invoice_number }}" readonly />
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input wire:model.live="date" type="date" class="form-control" />
                        @error('date')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label class="form-label">Nama Toko</label>
                        <input wire:model.live="store_name" type="text" class="form-control" placeholder="Nama toko" />
                        @error('store_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5 class="mb-0">Daftar Produk Operasional</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input wire:model.live="new_product_name" type="text" class="form-control" placeholder="Nama produk" />
                        @error('new_product_name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input wire:model.live="new_quantity" type="number" min="1" step="1" class="form-control" />
                        @error('new_quantity')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="mb-3">
                        <label class="form-label">Harga Satuan</label>
                        <input wire:model.live="new_unit_price" type="number" min="0" step="0.01" class="form-control" />
                        @error('new_unit_price')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Item</label>
                        <input wire:model.live="new_line_description" type="text" class="form-control" placeholder="Opsional" />
                    </div>
                </div>
            </div>
            <button type="button" wire:click="addLineItem" class="btn btn-primary mb-4">
                <i class="bi bi-plus-circle"></i> Tambah Item
            </button>

            @error('line_items')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            @if (count($line_items))
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($line_items as $index => $item)
                                <tr>
                                    <td>{{ $item['product_name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}</td>
                                    <td>{{ $item['description'] ?? '-' }}</td>
                                    <td>
                                        <button type="button" wire:click="removeLineItem({{ $index }})" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mb-3">
                    <strong>Total Tagihan: Rp {{ number_format(collect($line_items)->sum(fn($item) => $item['quantity'] * $item['unit_price']), 0, ',', '.') }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5 class="mb-0">Detail Pembayaran</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Keterangan Umum</label>
                <textarea wire:model.live="description" class="form-control" rows="3" placeholder="Keterangan (opsional)"></textarea>
            </div>

            <div class="form-check mb-3">
                <input wire:model.live="is_paid" type="checkbox" class="form-check-input" id="isPaid" />
                <label class="form-check-label" for="isPaid">Langsung Lunasi</label>
            </div>

            @if ($is_paid)
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pembayaran</label>
                            <input wire:model.live="payment_date" type="date" class="form-control" />
                            @error('payment_date')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Akun Kas</label>
                            <select wire:model.live="cash_account_id" class="form-select">
                                <option value="">-- Pilih Akun Kas --</option>
                                @foreach ($cashAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('cash_account_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-3">
                <button type="submit" wire:loading.attr="disabled" class="btn btn-dark text-inv-primary me-2">
                    <span wire:loading.remove><i class="bi bi-check-circle"></i> Simpan</span>
                    <span wire:loading><i class="bi bi-arrow-repeat bi-spin"></i> Menyimpan...</span>
                </button>
                <a href="{{ route('admin.operational-expenses.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </div>
    </div>
    </form>
</div>

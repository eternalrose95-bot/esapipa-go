<div>
    <x-slot:header>Penjualan</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.sales.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Penjualan
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Penjualan</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>Tanggal</th>
                        <th>Klien</th>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Total Harga</th>
                        <th>Total Dibayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        @foreach ($sale->products as $product)
                            <tr>
                                <td>
                                    <h6>{{ Carbon\Carbon::parse($sale->sale_date)->format('jS F,Y') }}</h6>
                                </td>
                                <td>
                                    {{ $sale->client->name }}
                                </td>
                                <td>
                                    {{ $product->name }}
                                </td>
                                <td>
                                    {{ $product->size ? $product->size->name : 'N/A' }}
                                </td>
                                <td>
                                    {{ number_format($product->pivot->quantity) }}
                                </td>
                                <td>
                                    {{ $product->pivot->notes ?: '-' }}
                                </td>
                                <td>
                                    <small>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <small>Rp {{ number_format($sale->total_paid, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <span class="{{ $sale->is_paid ? 'text-success' : 'text-danger' }}" style="font: bold">
                                        {{ $sale->is_paid ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a wire:navigate href="{{ route('admin.sales.edit', $sale->id) }}"
                                        class="btn btn-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if (!$sale->is_paid)
                                        <button class="btn btn-info" wire:click="openPaymentModal({{ $sale->id }})">
                                            <i class="bi bi-credit-card"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.sales.invoice', $sale->id) }}" target="_blank" class="btn btn-primary">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                    <button
                                        onclick="confirm('Are you sure you wish to delete this Sale?')||event.stopImmediatePropagation()"
                                        class="btn btn-danger" wire:click='delete({{ $sale->id }})'>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="6"><strong>TOTALS</strong></td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $sales->sum(function ($sale) {
                                        return $sale->total_amount;
                                    }),
                                ) }}
                            </strong>
                        </td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $sales->sum(function ($sale) {
                                        return $sale->total_paid;
                                    }),
                                ) }}
                            </strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="modal fade show d-block" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-inv-secondary text-inv-primary border-0">
                        <h5 class="modal-title" id="paymentModalLabel">Tambah Pembayaran Penjualan</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePaymentModal()" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="savePayment">
                            <div class="mb-3">
                                <label for="payment_time" class="form-label">Tanggal & Waktu Pembayaran</label>
                                <input type="datetime-local" class="form-control" id="payment_time" wire:model="payment_time" required>
                                @error('payment_time') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah</label>
                                <input type="text" class="form-control" id="amount" wire:model="amount" inputmode="decimal" required>
                                @error('amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cash_account_id" class="form-label">Akun Kas</label>
                                <select class="form-control" id="cash_account_id" wire:model="cash_account_id" required>
                                    <option value="">Pilih Akun Kas</option>
                                    @foreach ($cashAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('cash_account_id') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="transaction_reference" class="form-label">Referensi Transaksi</label>
                                <input type="text" class="form-control" id="transaction_reference" wire:model="transaction_reference" required>
                                @error('transaction_reference') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closePaymentModal()">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

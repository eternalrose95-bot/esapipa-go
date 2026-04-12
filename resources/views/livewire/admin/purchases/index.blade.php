<div>
    <x-slot:header>Pembelian</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Belanja
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Pembelian</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover  ">
                <thead class="thead-inverse">
                    <tr>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Ukuran</th>
                        <th>Keterangan</th>
                        <th>Total Pembelian</th>
                        <th>Total Dibayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        @if($purchase->products->count() > 0)
                            @foreach($purchase->products as $index => $product)
                                <tr>
                                    @if($index == 0)
                                        <td rowspan="{{ $purchase->products->count() }}">
                                            <h6>{{ Carbon\Carbon::parse($purchase->purchase_date)->format('jS F,Y') }}</h6>
                                        </td>
                                        <td rowspan="{{ $purchase->products->count() }}">
                                            {{ $purchase->supplier?->name ?? '-' }}
                                        </td>
                                    @endif
                                    <td>
                                        {{ $product->name }}
                                    </td>
                                    <td>
                                        {{ number_format($product->pivot->quantity) }}
                                    </td>
                                    <td>
                                        {{ $product->size?->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if($product->pivot->notes)
                                            <small><em>{{ $product->pivot->notes }}</em></small>
                                        @else
                                            <small>-</small>
                                        @endif
                                    </td>
                                    @if($index == 0)
                                        <td rowspan="{{ $purchase->products->count() }}">
                                            <small>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</small>
                                        </td>
                                        <td rowspan="{{ $purchase->products->count() }}">
                                            <small>Rp {{ number_format($purchase->total_paid, 0, ',', '.') }}</small>
                                        </td>
                                        <td rowspan="{{ $purchase->products->count() }}">
                                            <span class="{{ $purchase->is_paid ? 'text-success' : 'text-danger' }}"
                                                style="font: bold">
                                                {{ $purchase->is_paid ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                        <td rowspan="{{ $purchase->products->count() }}" class="text-center">
                                            <a wire:navigate href="{{ route('admin.purchases.edit', $purchase->id) }}"
                                                class="btn btn-secondary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if (!$purchase->is_paid)
                                                <button class="btn btn-info" wire:click="openPaymentModal({{ $purchase->id }})">
                                                    <i class="bi bi-credit-card"></i>
                                                </button>
                                            @endif
                                            <button
                                                onclick="confirm('Are you sure you wish to delete this Purchase?')||event.stopImmediatePropagation()"
                                                class="btn btn-danger" wire:click='delete({{ $purchase->id }})'>
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <h6>{{ Carbon\Carbon::parse($purchase->purchase_date)->format('jS F,Y') }}</h6>
                                </td>
                                <td>
                                    {{ $purchase->supplier?->name ?? '-' }}
                                </td>
                                <td colspan="3">
                                    <small>-</small>
                                </td>
                                <td>
                                    <small>-</small>
                                </td>
                                <td>
                                    <small>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <small>Rp {{ number_format($purchase->total_paid, 0, ',', '.') }}</small>
                                </td>
                                <td>
                                    <span class="{{ $purchase->is_paid ? 'text-success' : 'text-danger' }}"
                                        style="font: bold">
                                        {{ $purchase->is_paid ? 'Lunas' : 'Belum Lunas' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a wire:navigate href="{{ route('admin.purchases.edit', $purchase->id) }}"
                                        class="btn btn-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if (!$purchase->is_paid)
                                        <button class="btn btn-info" wire:click="openPaymentModal({{ $purchase->id }})">
                                            <i class="bi bi-credit-card"></i>
                                        </button>
                                    @endif
                                    <button
                                        onclick="confirm('Are you sure you wish to delete this Purchase?')||event.stopImmediatePropagation()"
                                        class="btn btn-danger" wire:click='delete({{ $purchase->id }})'>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="6"><strong>TOTALS</strong></td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $purchases->sum(function ($purchase) {
                                        return $purchase->total_amount;
                                    }),
                                ) }}
                            </strong>
                        </td>
                        <td>
                            <strong>
                                Rp
                                {{ number_format(
                                    $purchases->sum(function ($purchase) {
                                        return $purchase->total_paid;
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
                        <h5 class="modal-title" id="paymentModalLabel">Tambah Pembayaran Pembelian</h5>
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

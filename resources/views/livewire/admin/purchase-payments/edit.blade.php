<div>
    <x-slot:header>Pelunasan Pembelian</x-slot:header>

    <div class="row justify-content-center">
        <div class="col-md-6 col-4">
            <div class="card">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Atur Tanggal Dan Supplier</h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="mb-3">
                            <label for="" class="form-label">Tanggal Pembelian</label>
                            <input wire:model='purchase_payment.payment_time' type="datetime-local"
                                class="form-control" />
                            @error('purchase_payment.payment_time')
                                <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ref" class="form-label">Nomor PO</label>
                            <input
                            wire:model='purchase_payment.transaction_reference'
                                type="text"
                                class="form-control"
                                name="ref"
                                id="ref"
                                aria-describedby="ref"
                                placeholder="Enter your transaction reference"
                            />

                        </div>


                        <div class="mb-3">
                            <label for="" class="form-label">Supplier</label>
                            <input type="text" wire:model.live='supplierSearch' class="form-control" />
                            @error('purchase_payment.supplier_id')
                                <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <ul class="list-group mt-2 w-100">
                                @if ($supplierSearch != '')
                                    @foreach ($suppliers as $supplier)
                                        <x-supplier-payment-list-item :supplier="$supplier" :purchase_payment="$purchase_payment" />
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Akun Kas</label>
                            <select wire:model='purchase_payment.cash_account_id' class="form-select">
                                <option value="">Pilih Akun Kas</option>
                                @foreach ($cashAccounts as $cashAccount)
                                    <option value="{{ $cashAccount->id }}">{{ $cashAccount->name }} {{ $cashAccount->account_number ? '('.$cashAccount->account_number.')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('purchase_payment.cash_account_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Total Jumlah</label>
                            <div class="input-group">
                                <input wire:model='purchase_payment.amount' type="number" class="form-control" disabled/>
                                <button wire:click='savePayment' class="btn btn-outline-secondary">
                                    <i class="bi bi-wallet"></i>
                                </button>
                            </div>
                            @error('purchase_payment.amount')
                                <small id="helpId" class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <button
                        onclick="confirm('Yakin ingin melunasi hutang ke supplier ini?')||event.stopImmediatePropagation()"
                        wire:click='savePayment' class="btn btn-success w-100">Lunasi</button>
                </div>
            </div>
        </div>
    </div>
</div>

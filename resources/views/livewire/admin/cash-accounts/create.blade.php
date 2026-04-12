<div>
    <x-slot:header>Kas</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Tambah Akun Kas Baru</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Akun Kas</label>
                        <input wire:model="cashAccount.name" type="text" class="form-control" id="name"
                            placeholder="Nama Akun Kas" />
                        @error('cashAccount.name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="account_number" class="form-label">Nomor Rekening (opsional)</label>
                        <input wire:model="cashAccount.account_number" type="text" class="form-control" id="account_number"
                            placeholder="Contoh: 001-123456" />
                        @error('cashAccount.account_number')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="opening_balance" class="form-label">Saldo</label>
                        <input wire:model="cashAccount.opening_balance" type="text" inputmode="decimal" class="form-control" id="opening_balance"
                            placeholder="0.00" />
                        @error('cashAccount.opening_balance')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <input wire:model="cashAccount.notes" type="text" class="form-control" id="notes"
                            placeholder="Keterangan tambahan" />
                        @error('cashAccount.notes')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <button onclick="confirm('Simpan akun kas baru?')||event.stopImmediatePropagation()"
                wire:click="save" class="btn btn-dark text-inv-primary">Simpan</button>
        </div>
    </div>
</div>

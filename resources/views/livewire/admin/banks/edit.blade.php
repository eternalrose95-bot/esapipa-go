<div>
    <x-slot:header>Bank</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Edit Akun Bank</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Bank</label>
                        <input wire:model.live='bank.name' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter your Bank Name" />
                        @error('bank.name')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Singkatan Bank</label>
                        <input wire:model.live='bank.short_name' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter your Bank Short Name" />
                        @error('bank.short_name')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Kode Cabang Bank</label>
                        <input wire:model.live='bank.sort_code' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter your Bank SORT Code" />
                        @error('bank.sort_code')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>



            <button onclick="confirm('Are you sure you wish to update this Bank')||event.stopImmediatePropagation()"
                wire:click='save' class="btn btn-dark text-inv-primary">Save</button>
        </div>
    </div>
</div>

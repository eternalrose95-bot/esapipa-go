<div>
    <x-slot:header>Klien</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Edit Klien Ini</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input wire:model.live='client.name' type="text" class="form-control" name="name"
                            id="name" aria-describedby="name" placeholder="Enter your Client's Name" />
                        @error('client.name')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Alamat Email</label>
                        <input wire:model.live='client.email' type="email" class="form-control" name="email"
                            id="name" aria-describedby="email" placeholder="Enter your Client's Email Address" />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nomor HP</label>
                        <input wire:model.live='client.phone_number' type="text" class="form-control" name="phone_number"
                            id="name" aria-describedby="phone_number" placeholder="Enter Phone Number" />
                        @error('client.phone_number')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">NPWP</label>
                        <input wire:model.live='client.tax_id' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter Tax ID" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Alamat</label>
                    <textarea wire:model.live='client.address' class="form-control" name="" id="" rows="3"></textarea>
                    @error('client.address')
                        <small id="" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Bank</label>
                    <select wire:model.live='client.bank_id' class="form-select " name=""
                        id="">
                        <option selected>Select your Bank</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nomor Rekening</label>
                    <input wire:model.live='client.account_number' type="text" class="form-control" name="name"
                        id="name" aria-describedby="" placeholder="Enter Client's Account Number" />
                </div>



            </div>



            <button onclick="confirm('Are you sure you wish to create this Client')||event.stopImmediatePropagation()"
                wire:click='save' class="btn btn-dark text-inv-primary">Save</button>
        </div>
    </div>
</div>

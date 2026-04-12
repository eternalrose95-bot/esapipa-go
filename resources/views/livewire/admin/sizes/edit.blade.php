<div>
    <x-slot:header>Ukuran</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Ubah Ukuran</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Ukuran</label>
                        <input wire:model.live='size.name' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter your Size Name" />
                        @error('size.name')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>



            <button onclick="confirm('Are you sure you wish to update this Size')||event.stopImmediatePropagation()"
                wire:click='save' class="btn btn-dark text-inv-primary">Update</button>
        </div>
    </div>
</div>

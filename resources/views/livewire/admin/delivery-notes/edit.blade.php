<div>
    <x-slot:header>Edit Surat Jalan</x-slot:header>

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="driver_name" class="form-label">Nama Driver</label>
                            <input type="text" class="form-control" id="driver_name" wire:model="driver_name" required>
                            @error('driver_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Nomor Kendaraan</label>
                            <input type="text" class="form-control" id="vehicle_number" wire:model="vehicle_number" required>
                            @error('vehicle_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="receiver_name" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" id="receiver_name" wire:model="receiver_name" required>
                            @error('receiver_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sale_id" class="form-label">Pilih Penjualan</label>
                            <select class="form-control" id="sale_id" wire:model="sale_id" required>
                                <option value="">Pilih Penjualan</option>
                                @foreach($sales as $sale)
                                    <option value="{{ $sale->id }}">{{ $sale->id }} - {{ $sale->client->name }}</option>
                                @endforeach
                            </select>
                            @error('sale_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.delivery-notes.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

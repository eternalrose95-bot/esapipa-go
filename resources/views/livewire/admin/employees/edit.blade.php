<div>
    <x-slot:header>Edit SDM</x-slot:header>

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" wire:model="name" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="position" wire:model="position" required>
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="birth_place" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="birth_place" wire:model="birth_place">
                        </div>
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="birth_date" wire:model="birth_date">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="gender" wire:model="gender">
                                <option value="">Pilih</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" wire:model="address"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="phone" wire:model="phone">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" wire:model="email">
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" wire:model="nik">
                        </div>
                        <div class="mb-3">
                            <label for="join_date" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="join_date" wire:model="join_date">
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="bank_account" wire:model="bank_account">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
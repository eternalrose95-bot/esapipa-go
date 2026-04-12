<div>
    <x-slot:header>User</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Buat User Baru</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input wire:model.defer='user.name' type="text" class="form-control" name="name"
                            id="name" aria-describedby="" placeholder="Enter your User's Name" />
                        @error('user.name')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input wire:model.defer='user.email' type="email" class="form-control" name="email"
                            id="email" aria-describedby="" placeholder="Enter your User's Email Address" />
                        @error('user.email')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group mb-3">
                        <label for="">Peran</label>
                        <select wire:model='selectedRoles' multiple class="form-control" name="" id="">
                            @forelse ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->title }}</option>
                            @empty
                                <option disabled>Tidak Ada</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input wire:model.defer='user.password' type="password" class="form-control" name="password"
                            id="password" placeholder="Enter password" />
                        @error('user.password')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <button onclick="confirm('Are you sure you wish to create this user')||event.stopImmediatePropagation()"
                wire:click='save' class="btn btn-dark text-inv-primary">Simpan</button>
        </div>
    </div>
</div>

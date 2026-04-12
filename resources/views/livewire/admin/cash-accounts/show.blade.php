<div>
    <x-slot:header>Detail Kas</x-slot:header>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-12 mb-3">
            <div class="card">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Informasi Akun Kas</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nama Akun:</strong> {{ $cashAccount->name }}</p>
                    <p><strong>Nomor Akun:</strong> {{ $cashAccount->account_number ?? '-' }}</p>
                    <p><strong>Saldo Awal:</strong> {{ number_format($cashAccount->opening_balance, 0, ',', '.') }}</p>
                    <p><strong>Total Masuk:</strong> {{ number_format($cashAccount->total_in, 0, ',', '.') }}</p>
                    <p><strong>Total Keluar:</strong> {{ number_format($cashAccount->total_out, 0, ',', '.') }}</p>
                    <p><strong>Saldo Saat Ini:</strong> {{ number_format($cashAccount->current_balance, 0, ',', '.') }}</p>
                    <p><strong>Catatan:</strong> {{ $cashAccount->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-12 mb-3">
            <div class="card">
                <div class="card-header bg-inv-secondary text-inv-primary border-0">
                    <h5>Tambah Mutasi Kas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transaksi</label>
                                <input wire:model.live="transaction_date" type="date" class="form-control" />
                                @error('transaction_date')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input wire:model.live="amount" type="number" step="0.01" class="form-control" placeholder="0.00" />
                                @error('amount')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label class="form-label">Jenis Mutasi</label>
                                <select wire:model.live="type" class="form-select">
                                    <option value="in">Masuk</option>
                                    <option value="out">Keluar</option>
                                </select>
                                @error('type')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input wire:model.live="description" type="text" class="form-control" placeholder="Deskripsi transaksi" />
                        @error('description')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button onclick="confirm('Tambah mutasi kas?')||event.stopImmediatePropagation()"
                        wire:click="addTransaction" class="btn btn-dark text-inv-primary">Tambah Mutasi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Mutasi Kas</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cashAccount->transactions->sortByDesc('transaction_date')->sortByDesc('id') as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->type === 'in' ? 'success' : 'danger' }}">
                                    {{ $transaction->type === 'in' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>{{ $transaction->description ?? '-' }}</td>
                            <td class="text-center">
                                <button onclick="confirm('Hapus mutasi ini?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger btn-sm" wire:click="deleteTransaction({{ $transaction->id }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada mutasi untuk akun kas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

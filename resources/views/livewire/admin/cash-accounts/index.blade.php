<div>
    <x-slot:header>Kas</x-slot:header>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card bg-inv-secondary text-inv-primary h-100">
                <div class="card-body">
                    <h6>Saldo Awal Total</h6>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format($totalOpeningBalance, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-inv-secondary text-inv-primary h-100 cursor-pointer" wire:click="showInTransactions" style="cursor: pointer;">
                <div class="card-body">
                    <h6>Total Masuk</h6>
                    <p class="fs-4 fw-bold mb-0 text-success">Rp {{ number_format($totalIn, 0, ',', '.') }}</p>
                    <small class="text-muted">Klik untuk melihat detail</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-inv-secondary text-inv-primary h-100 cursor-pointer" wire:click="showOutTransactions" style="cursor: pointer;">
                <div class="card-body">
                    <h6>Total Keluar</h6>
                    <p class="fs-4 fw-bold mb-0 text-danger">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
                    <small class="text-muted">Klik untuk melihat detail</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-inv-secondary text-inv-primary h-100">
                <div class="card-body">
                    <h6>Saldo Akhir Total</h6>
                    <p class="fs-4 fw-bold mb-0">Rp {{ number_format($totalCurrentBalance, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    @if ($showFilteredTransactions)
        <div class="card mb-4">
            <div class="card-header bg-inv-secondary text-inv-primary border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if ($filterType === 'in')
                        Mutasi Saldo Masuk
                    @else
                        Mutasi Saldo Keluar
                    @endif
                </h5>
                <button type="button" wire:click="clearFilter" class="btn btn-sm btn-secondary">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead class="thead-inverse">
                        <tr>
                            <th>No</th>
                            <th>Nama Akun</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($filteredTransactions as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $transaction['account_name'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaction['transaction_date'])->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction['type'] === 'in' ? 'success' : 'danger' }}">
                                        Rp {{ number_format($transaction['amount'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>{{ $transaction['description'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada mutasi untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.cash-accounts.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kas
        </a>
        <button type="button" wire:click="openTransferForm" class="btn btn-primary text-inv-primary">
            <i class="bi bi-arrow-left-right"></i> Transfer Kas
        </button>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5 class="mb-0">Daftar Akun Kas</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>No</th>
                        <th>Nama Akun</th>
                        <th>Nomor Akun</th>
                        <th>Saldo Awal</th>
                        <th>Total Masuk</th>
                        <th>Total Keluar</th>
                        <th>Saldo Saat Ini</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cashAccounts as $cashAccount)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cashAccount->name }}</td>
                            <td>{{ $cashAccount->account_number ?? '-' }}</td>
                            <td>{{ number_format($cashAccount->opening_balance, 0, ',', '.') }}</td>
                            <td>{{ number_format($cashAccount->total_in, 0, ',', '.') }}</td>
                            <td>{{ number_format($cashAccount->total_out, 0, ',', '.') }}</td>
                            <td>{{ number_format($cashAccount->current_balance, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.cash-accounts.show', $cashAccount->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.cash-accounts.edit', $cashAccount->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button onclick="confirm('Yakin ingin menghapus akun kas ini?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger btn-sm" wire:click="delete({{ $cashAccount->id }})">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada akun kas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($showTransferForm)
        <div class="card mt-4">
            <div class="card-header bg-inv-secondary text-inv-primary border-0">
                <h5 class="mb-0">Transfer Kas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label for="fromAccount" class="form-label">Dari Akun</label>
                            <select wire:model.live="fromAccountId" id="fromAccount" class="form-select">
                                <option value="">Pilih Akun Sumber</option>
                                @foreach ($cashAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} (Rp {{ number_format($account->current_balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            @error('fromAccountId')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label for="toAccount" class="form-label">Ke Akun</label>
                            <select wire:model.live="toAccountId" id="toAccount" class="form-select">
                                <option value="">Pilih Akun Tujuan</option>
                                @foreach ($cashAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('toAccountId')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="mb-3">
                            <label for="transferAmount" class="form-label">Jumlah</label>
                            <input wire:model.live="transferAmount" id="transferAmount" type="number" min="0" step="1" class="form-control" placeholder="Masukkan jumlah transfer">
                            @error('transferAmount')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="button" wire:click="transfer" class="btn btn-success me-2">
                    <i class="bi bi-check-circle"></i> Transfer
                </button>
                <button type="button" wire:click="closeTransferForm" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
            </div>
        </div>
    @endif
</div>

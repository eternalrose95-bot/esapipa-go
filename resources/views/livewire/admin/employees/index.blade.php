<div>
    <x-slot:header>SDM</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.employees.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah SDM
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar SDM</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>Absensi</th>
                        <th>Cuti</th>
                        <th>Tanggal Gajian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->employees as $employee)
                        <tr>
                            <td>
                                <a href="#" wire:click="showDetail({{ $employee->id }})" class="text-decoration-none">
                                    {{ $employee->name }}
                                </a>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <span class="badge {{ $employee->is_active ? 'bg-success' : 'bg-danger' }} cursor-pointer" wire:click="toggleStatus({{ $employee->id }})">
                                    {{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="#" wire:click="showAttendanceHistory({{ $employee->id }})" class="text-decoration-none">
                                    {{ $employee->attendance_count_last_month }}
                                </a>
                            </td>
                            <td>
                                <a href="#" wire:click="showLeaveHistory({{ $employee->id }})" class="text-decoration-none">
                                    {{ $employee->leave_count }}
                                </a>
                            </td>
                            <td>
                                <a href="#" wire:click="showSalaryHistory({{ $employee->id }})" class="text-decoration-none">
                                    {{ $employee->salary->payday ?? 1 }}
                                </a>
                                <button class="btn btn-sm btn-outline-secondary ms-1" wire:click="showEditPayday({{ $employee->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <a wire:navigate href="{{ route('admin.employees.edit', $employee->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Employee?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $employee->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail SDM</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($selectedEmployee)
                            <p><strong>Nama:</strong> {{ $selectedEmployee->name }}</p>
                            <p><strong>Usia:</strong> {{ $selectedEmployee->age }}</p>
                            <p><strong>Tempat & Tanggal Lahir:</strong> {{ $selectedEmployee->birth_place }}, {{ $selectedEmployee->birth_date?->format('d/m/Y') }}</p>
                            <p><strong>Jenis Kelamin:</strong> {{ $selectedEmployee->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                            <p><strong>Alamat:</strong> {{ $selectedEmployee->address }}</p>
                            <p><strong>No. HP:</strong> {{ $selectedEmployee->phone }}</p>
                            <p><strong>Email:</strong> {{ $selectedEmployee->email }}</p>
                            <p><strong>NIK:</strong> {{ $selectedEmployee->nik }}</p>
                            <p><strong>Tanggal Masuk:</strong> {{ $selectedEmployee->join_date?->format('d/m/Y') }}</p>
                            <p><strong>Nomor Rekening:</strong> {{ $selectedEmployee->bank_account }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Attendance History Modal -->
    @if($showAttendanceHistoryModal)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat Absensi - {{ $selectedEmployee?->name }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($showAddAttendance)
                            <form wire:submit.prevent="saveAttendance">
                                <div class="mb-3">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control" wire:model="attendance_date" required>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" wire:model="attendance_reason"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" wire:click="$set('showAddAttendance', false)">Batal</button>
                            </form>
                        @else
                            <div class="mb-3">
                                <button class="btn btn-primary" wire:click="showAttendance">Tambah Absensi</button>
                            </div>
                            @if($selectedEmployee && $selectedEmployee->attendances->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedEmployee->attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                                <td>{{ $attendance->reason ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>Belum ada riwayat absensi.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Leave History Modal -->
    @if($showLeaveHistoryModal)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat Cuti - {{ $selectedEmployee?->name }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($showAddLeave)
                            <form wire:submit.prevent="saveLeave">
                                <div class="mb-3">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" class="form-control" wire:model="leave_start" required>
                                </div>
                                <div class="mb-3">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" class="form-control" wire:model="leave_end" required>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" wire:model="leave_reason"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" wire:click="$set('showAddLeave', false)">Batal</button>
                            </form>
                        @else
                            <div class="mb-3">
                                <button class="btn btn-primary" wire:click="showLeave">Ajukan Cuti</button>
                            </div>
                            @if($selectedEmployee && $selectedEmployee->leaves->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Akhir</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedEmployee->leaves as $leave)
                                            <tr>
                                                <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                                                <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                                                <td>{{ $leave->reason ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>Belum ada riwayat cuti.</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Salary History Modal -->
    @if($showSalaryHistoryModal)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Riwayat Gajian - {{ $selectedEmployee?->name }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($selectedEmployee && $selectedEmployee->salaryPayments->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Akun Kas</th>
                                        <th>Referensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selectedEmployee->salaryPayments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            <td>{{ $payment->cashAccount->name }}</td>
                                            <td>{{ $payment->transaction_reference }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Belum ada riwayat pembayaran gaji.</p>
                        @endif
                        <hr>
                        <h6>Bayar Gaji Sekarang</h6>
                        <form wire:submit.prevent="paySalary">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal Pembayaran</label>
                                        <input type="date" class="form-control" wire:model="payment_date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Jumlah</label>
                                        <input type="number" class="form-control" wire:model="payment_amount" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Akun Kas</label>
                                        <select class="form-control" wire:model="cash_account_id" required>
                                            <option value="">Pilih Akun Kas</option>
                                            @foreach($cashAccounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Referensi Transaksi</label>
                                        <input type="text" class="form-control" wire:model="transaction_reference" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Bayar Gaji</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Edit Payday Modal -->
    @if($showEditPaydayModal)
        <div class="modal fade show d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Tanggal Gajian - {{ $selectedEmployee?->name }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="savePayday">
                            <div class="mb-3">
                                <label>Tanggal Gajian (1-31)</label>
                                <input type="number" class="form-control" wire:model="payday" min="1" max="31" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
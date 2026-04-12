<div>
    <x-slot:header>Surat Jalan</x-slot:header>

    <div class="mb-3">
        <a href="{{ route('admin.delivery-notes.create') }}" class="btn btn-dark text-inv-primary">
            <i class="bi bi-plus-circle"></i> Tambah Surat Jalan
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Daftar Surat Jalan</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="thead-inverse">
                    <tr>
                        <th>Driver</th>
                        <th>Kendaraan</th>
                        <th>Penerima</th>
                        <th>Klien</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveryNotes as $note)
                        <tr>
                            <td>{{ $note->driver_name }}</td>
                            <td>{{ $note->vehicle_number }}</td>
                            <td>{{ $note->receiver_name }}</td>
                            <td>{{ $note->sale->client->name }}</td>
                            <td class="text-center">
                                <a wire:navigate href="{{ route('admin.delivery-notes.edit', $note->id) }}"
                                    class="btn btn-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ route('admin.delivery-notes.pdf', $note->id) }}" target="_blank" class="btn btn-primary">
                                    <i class="bi bi-file-earmark-text"></i>
                                </a>
                                <button
                                    onclick="confirm('Are you sure you wish to delete this Delivery Note?')||event.stopImmediatePropagation()"
                                    class="btn btn-danger" wire:click='delete({{ $note->id }})'>
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

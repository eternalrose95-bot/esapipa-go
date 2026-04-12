<?php

namespace App\Livewire\Admin\DeliveryNotes;

use App\Models\DeliveryNote;
use App\Models\Sale;
use Livewire\Component;

class Edit extends Component
{
    public $deliveryNote;
    public $driver_name;
    public $vehicle_number;
    public $receiver_name;
    public $sale_id;

    protected $rules = [
        'driver_name' => 'required|string',
        'vehicle_number' => 'required|string',
        'receiver_name' => 'required|string',
        'sale_id' => 'required|exists:sales,id',
    ];

    public function mount($deliveryNote)
    {
        $this->deliveryNote = $deliveryNote;
        $this->driver_name = $deliveryNote->driver_name;
        $this->vehicle_number = $deliveryNote->vehicle_number;
        $this->receiver_name = $deliveryNote->receiver_name;
        $this->sale_id = $deliveryNote->sale_id;
    }

    public function save()
    {
        $this->validate();
        $this->deliveryNote->update($this->only(['driver_name', 'vehicle_number', 'receiver_name', 'sale_id']));
        return redirect()->route('admin.delivery-notes.index')->with('success', 'Delivery Note updated');
    }

    public function render()
    {
        $sales = Sale::with('client')->get();
        return view('livewire.admin.delivery-notes.edit', compact('sales'));
    }
}

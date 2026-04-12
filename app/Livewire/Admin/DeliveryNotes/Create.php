<?php

namespace App\Livewire\Admin\DeliveryNotes;

use App\Models\DeliveryNote;
use App\Models\Sale;
use Livewire\Component;

class Create extends Component
{
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

    public function save()
    {
        $this->validate();
        DeliveryNote::create($this->only(['driver_name', 'vehicle_number', 'receiver_name', 'sale_id']));
        return redirect()->route('admin.delivery-notes.index')->with('success', 'Delivery Note created');
    }

    public function render()
    {
        $sales = Sale::with('client')->get();
        return view('livewire.admin.delivery-notes.create', compact('sales'));
    }
}

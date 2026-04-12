<?php

namespace App\Livewire\Admin\DeliveryNotes;

use App\Models\DeliveryNote;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        DeliveryNote::findOrFail($id)->delete();
        $this->dispatch('done', success: 'Delivery Note deleted');
    }

    public function render()
    {
        $deliveryNotes = DeliveryNote::with('sale.client')->get();
        return view('livewire.admin.delivery-notes.index', compact('deliveryNotes'));
    }
}

<?php

namespace App\Livewire\Admin\Suppliers;

use App\Models\Bank;
use App\Models\Supplier;
use Livewire\Component;

class Create extends Component
{
    public Supplier $supplier;

    function rules()
    {
        return [
            'supplier.name' => "required",
            'supplier.email' => "nullable",
            'supplier.address' => "required",
            'supplier.phone_number' => "required",
            'supplier.tax_id' => "nullable",
            'supplier.bank_id' => "nullable",
            'supplier.account_number' => "nullable",
        ];
    }

    function mount()
    {
        $this->supplier = new Supplier();
    }

    function updated()
    {
        $this->validate();
    }

    function save()
    {
        $this->validate();
        try {
            $this->supplier->save();
            return redirect()->route('admin.suppliers.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        return view(
            'livewire.admin.suppliers.create',
            [
                'banks' => Bank::all()
            ]
        );
    }
}

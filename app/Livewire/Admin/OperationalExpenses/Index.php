<?php

namespace App\Livewire\Admin\OperationalExpenses;

use App\Models\OperationalExpense;
use Livewire\Component;

class Index extends Component
{
    public $operationalExpenses;

    public function mount()
    {
        $this->loadExpenses();
    }

    public function loadExpenses()
    {
        $this->operationalExpenses = OperationalExpense::with('cashAccount')
            ->orderBy('date', 'desc')
            ->get();
    }

    public function delete($id)
    {
        $expense = OperationalExpense::findOrFail($id);
        $expense->delete();

        session()->flash('success', 'Belanja operasional berhasil dihapus.');

        $this->loadExpenses();
    }

    public function render()
    {
        return view('livewire.admin.operational-expenses.index');
    }
}

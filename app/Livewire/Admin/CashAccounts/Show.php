<?php

namespace App\Livewire\Admin\CashAccounts;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use Livewire\Component;

class Show extends Component
{
    public CashAccount $cashAccount;
    public $transaction_date;
    public $amount;
    public $type = 'in';
    public $description;

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:in,out',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function mount($id)
    {
        $this->cashAccount = CashAccount::with('transactions')->findOrFail($id);
        $this->transaction_date = now()->toDateString();
    }

    public function addTransaction()
    {
        $this->validate();

        $this->cashAccount->transactions()->create([
            'transaction_date' => $this->transaction_date,
            'amount' => $this->amount,
            'type' => $this->type,
            'description' => $this->description,
        ]);

        $this->reset(['transaction_date', 'amount', 'type', 'description']);
        $this->transaction_date = now()->toDateString();
        $this->cashAccount->refresh();

        session()->flash('success', 'Mutasi kas berhasil ditambahkan.');
    }

    public function deleteTransaction($id)
    {
        $transaction = CashTransaction::where('cash_account_id', $this->cashAccount->id)->findOrFail($id);
        $transaction->delete();
        $this->cashAccount->refresh();

        session()->flash('success', 'Mutasi kas berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.cash-accounts.show');
    }
}

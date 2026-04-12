<?php

namespace App\Livewire\Admin\CashAccounts;

use App\Models\CashAccount;
use Livewire\Component;

class Create extends Component
{
    public CashAccount $cashAccount;

    protected function rules()
    {
        return [
            'cashAccount.name' => 'required|string|max:255',
            'cashAccount.account_number' => 'nullable|string|max:100',
            'cashAccount.opening_balance' => 'nullable|numeric|min:0',
            'cashAccount.notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->cashAccount = new CashAccount();
    }

    public function save()
    {
        $this->validate();
        $this->cashAccount->save();

        return redirect()->route('admin.cash-accounts.index');
    }

    public function render()
    {
        return view('livewire.admin.cash-accounts.create');
    }
}

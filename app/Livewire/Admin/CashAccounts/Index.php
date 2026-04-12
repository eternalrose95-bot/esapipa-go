<?php

namespace App\Livewire\Admin\CashAccounts;

use App\Models\CashAccount;
use Livewire\Component;

class Index extends Component
{
    public $cashAccounts;
    public $totalOpeningBalance = 0;
    public $totalIn = 0;
    public $totalOut = 0;
    public $totalCurrentBalance = 0;

    // Transfer properties
    public $showTransferForm = false;
    public $fromAccountId = null;
    public $toAccountId = null;
    public $transferAmount = null;

    // Filter properties
    public $showFilteredTransactions = false;
    public $filterType = null; // 'in' atau 'out'
    public $filteredTransactions = [];

    public function mount()
    {
        $this->loadAccounts();
    }

    public function loadAccounts()
    {
        $this->cashAccounts = CashAccount::with('transactions')->orderBy('name')->get();
        $this->totalOpeningBalance = $this->cashAccounts->sum('opening_balance');
        $this->totalIn = $this->cashAccounts->sum('total_in');
        $this->totalOut = $this->cashAccounts->sum('total_out');
        $this->totalCurrentBalance = $this->cashAccounts->sum('current_balance');
    }

    public function openTransferForm()
    {
        $this->showTransferForm = true;
    }

    public function closeTransferForm()
    {
        $this->showTransferForm = false;
        $this->fromAccountId = null;
        $this->toAccountId = null;
        $this->transferAmount = null;
    }

    public function showInTransactions()
    {
        $this->filterType = 'in';
        $this->filteredTransactions = [];
        
        foreach ($this->cashAccounts as $account) {
            foreach ($account->transactions as $transaction) {
                if ($transaction->type === 'in') {
                    $this->filteredTransactions[] = [
                        'id' => $transaction->id,
                        'account_name' => $account->name,
                        'account_id' => $account->id,
                        'transaction_date' => $transaction->transaction_date,
                        'amount' => $transaction->amount,
                        'description' => $transaction->description,
                        'type' => $transaction->type,
                    ];
                }
            }
        }
        
        // Sort by date descending
        usort($this->filteredTransactions, function($a, $b) {
            return $b['transaction_date']->timestamp - $a['transaction_date']->timestamp;
        });
        
        $this->showFilteredTransactions = true;
    }

    public function showOutTransactions()
    {
        $this->filterType = 'out';
        $this->filteredTransactions = [];
        
        foreach ($this->cashAccounts as $account) {
            foreach ($account->transactions as $transaction) {
                if ($transaction->type === 'out') {
                    $this->filteredTransactions[] = [
                        'id' => $transaction->id,
                        'account_name' => $account->name,
                        'account_id' => $account->id,
                        'transaction_date' => $transaction->transaction_date,
                        'amount' => $transaction->amount,
                        'description' => $transaction->description,
                        'type' => $transaction->type,
                    ];
                }
            }
        }
        
        // Sort by date descending
        usort($this->filteredTransactions, function($a, $b) {
            return $b['transaction_date']->timestamp - $a['transaction_date']->timestamp;
        });
        
        $this->showFilteredTransactions = true;
    }

    public function clearFilter()
    {
        $this->showFilteredTransactions = false;
        $this->filterType = null;
        $this->filteredTransactions = [];
    }

    public function transfer()
    {
        // Validate
        $this->validate([
            'fromAccountId' => 'required|exists:cash_accounts,id',
            'toAccountId' => 'required|exists:cash_accounts,id|different:fromAccountId',
            'transferAmount' => 'required|numeric|min:0.01',
        ]);

        try {
            $fromAccount = CashAccount::findOrFail($this->fromAccountId);
            $toAccount = CashAccount::findOrFail($this->toAccountId);

            // Check if from account has enough balance
            if ($fromAccount->current_balance < $this->transferAmount) {
                $this->dispatch('done', error: 'Saldo akun sumber tidak mencukupi');
                return;
            }

            // Create transactions
            $fromAccount->transactions()->create([
                'type' => 'out',
                'amount' => $this->transferAmount,
                'description' => "Transfer ke {$toAccount->name}",
                'transaction_date' => now(),
            ]);

            $toAccount->transactions()->create([
                'type' => 'in',
                'amount' => $this->transferAmount,
                'description' => "Transfer dari {$fromAccount->name}",
                'transaction_date' => now(),
            ]);

            $this->dispatch('done', success: 'Transfer berhasil dilakukan');
            $this->closeTransferForm();
            $this->loadAccounts();

        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Error: " . $th->getMessage());
        }
    }

    public function delete($id)
    {
        $cashAccount = CashAccount::findOrFail($id);
        $cashAccount->transactions()->delete();
        $cashAccount->delete();

        session()->flash('success', 'Akun kas berhasil dihapus.');

        return redirect()->route('admin.cash-accounts.index');
    }

    public function render()
    {
        return view('livewire.admin.cash-accounts.index');
    }
}

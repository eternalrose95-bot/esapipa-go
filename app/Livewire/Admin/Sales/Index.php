<?php

namespace App\Livewire\Admin\Sales;

use App\Models\Sale;
use App\Models\SalesPayment;
use App\Models\CashAccount;
use Livewire\Component;

class Index extends Component
{
    public $showPaymentModal = false;
    public $selectedSaleId = null;
    public $payment_time;
    public $amount = 0;
    public $cash_account_id;
    public $transaction_reference;

    function delete($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            if ($sale->is_paid) {
                throw new \Exception("Error Processing request: This Sale has already been paid for", 1);
            }

            $sale->products()->detach();
            $sale->delete();

            $this->dispatch('done', success: "Successfully Deleted this Sale");
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }

    public function openPaymentModal($saleId)
    {
        $sale = Sale::find($saleId);
        if (!$sale || $sale->is_paid) {
            $this->dispatch('done', error: "Cannot create payment for this sale");
            return;
        }

        $this->selectedSaleId = $saleId;
        $this->amount = $sale->total_balance;
        $this->payment_time = now()->format('Y-m-d\TH:i');
        $this->showPaymentModal = true;
    }


    public function resetPaymentForm()
    {
        $this->selectedSaleId = null;
        $this->amount = 0;
        $this->cash_account_id = null;
        $this->transaction_reference = '';
    }

    public function savePayment()
    {
        $this->validate([
            'payment_time' => 'required|date_format:Y-m-d\TH:i',
            'amount' => 'required|numeric|min:0.01',
            'cash_account_id' => 'required|exists:cash_accounts,id',
            'transaction_reference' => 'required|string',
        ]);

        try {
            $sale = Sale::find($this->selectedSaleId);
            if (!$sale) {
                throw new \Exception("Sale not found");
            }

            $payment = SalesPayment::create([
                'client_id' => $sale->client_id,
                'payment_time' => $this->payment_time,
                'amount' => $this->amount,
                'cash_account_id' => $this->cash_account_id,
                'transaction_reference' => $this->transaction_reference,
            ]);

            $payment->sales()->attach($sale->id, ['amount' => $this->amount]);
            $payment->recordCashTransaction();

            $this->dispatch('done', success: "Payment recorded successfully");
            $this->closePaymentModal();
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Error: " . $th->getMessage());
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetPaymentForm();
    }

    public function render()
    {
        return view('livewire.admin.sales.index', [
            'sales' => Sale::with(['client', 'payments', 'products.size'])->get(),
            'cashAccounts' => CashAccount::all(),
        ]);
    }
}

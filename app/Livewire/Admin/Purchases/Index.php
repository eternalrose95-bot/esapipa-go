<?php

namespace App\Livewire\Admin\Purchases;

use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\CashAccount;
use Livewire\Component;

class Index extends Component
{
    public $showPaymentModal = false;
    public $selectedPurchaseId = null;
    public $payment_time;
    public $amount = 0;
    public $cash_account_id;
    public $transaction_reference;

    function delete($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            if ($purchase->is_paid) {
                throw new \Exception("Error Processing request: This Purchase has already been paid for", 1);
            }

            $purchase->products()->detach();
            $purchase->delete();

            $this->dispatch('done', success: "Successfully Deleted this Purchase");
        } catch (\Throwable $th) {
            //throw $th;
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }

    public function openPaymentModal($purchaseId)
    {
        $purchase = Purchase::find($purchaseId);
        if (!$purchase || $purchase->is_paid) {
            $this->dispatch('done', error: "Cannot create payment for this purchase");
            return;
        }

        $this->selectedPurchaseId = $purchaseId;
        $this->amount = $purchase->total_balance;
        $this->payment_time = now()->format('Y-m-d\TH:i');
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetPaymentForm();
    }

    public function resetPaymentForm()
    {
        $this->selectedPurchaseId = null;
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
            $purchase = Purchase::find($this->selectedPurchaseId);
            if (!$purchase) {
                throw new \Exception("Purchase not found");
            }

            $payment = PurchasePayment::create([
                'supplier_id' => $purchase->supplier_id,
                'payment_time' => $this->payment_time,
                'amount' => $this->amount,
                'cash_account_id' => $this->cash_account_id,
                'transaction_reference' => $this->transaction_reference,
            ]);

            $payment->purchases()->attach($purchase->id, ['amount' => $this->amount]);
            $payment->recordCashTransaction();

            $this->dispatch('done', success: "Payment recorded successfully");
            $this->closePaymentModal();
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Error: " . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.purchases.index', [
            'purchases' => Purchase::with(['supplier', 'payments', 'products.size'])->get(),
            'cashAccounts' => CashAccount::all(),
        ]);
    }
}

<?php

namespace App\Livewire\Admin\PurchasePayments;

use App\Models\CashAccount;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $supplierSearch;
    public $cashAccounts;
    public PurchasePayment $purchase_payment;

    function rules()
    {
        return [
            'purchase_payment.supplier_id' => 'required',
            'purchase_payment.cash_account_id' => 'required',
            'purchase_payment.transaction_reference' => 'required',
            'purchase_payment.payment_time' => 'required',
        ];
    }

    function selectSupplier($id)
    {
        $this->purchase_payment->supplier_id = $id;
        $this->supplierSearch = $this->purchase_payment->supplier->name;
    }

    function mount($id)
    {
        $this->purchase_payment = PurchasePayment::findOrFail($id);

        $this->supplierSearch = $this->purchase_payment->supplier->name;
    }

    function savePayment()
    {
        try {
            $this->validate();

            $supplier = Supplier::with('purchases')->find($this->purchase_payment->supplier_id);

            if (!$supplier) {
                throw new \Exception("Supplier tidak ditemukan");
            }

            // hitung total hutang
            $totalDebt = $supplier->purchases->sum('total_balance');

            // update payment
            $this->purchase_payment->update([
                'supplier_id' => $this->purchase_payment->supplier_id,
                'cash_account_id' => $this->purchase_payment->cash_account_id,
                'transaction_reference' => $this->purchase_payment->transaction_reference,
                'payment_time' => $this->purchase_payment->payment_time,
                'amount' => $totalDebt,
            ]);

            // reset relasi
            $this->purchase_payment->purchases()->detach();

            // attach semua purchase yg masih punya hutang
            foreach ($supplier->purchases as $purchase) {
                if ($purchase->total_balance > 0) {
                    $this->purchase_payment->purchases()->attach($purchase->id, [
                        'amount' => $purchase->total_balance,
                    ]);
                }
            }

            $this->purchase_payment->recordCashTransaction();

            return redirect()->route('admin.purchase-payments.index');

        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->supplierSearch . '%')->get();
        $this->cashAccounts = CashAccount::orderBy('name')->get();
        return view('livewire.admin.purchase-payments.edit', [
            'suppliers' => $suppliers,
            'cashAccounts' => $this->cashAccounts,
        ]);
    }

    public function updatedPurchasePaymentSupplierId()
    {
        if (!$this->purchase_payment->supplier_id) return;

        $supplier = Supplier::with('purchases')->find($this->purchase_payment->supplier_id);

        $this->purchase_payment->amount = $supplier->purchases->sum('total_balance');
    }
}

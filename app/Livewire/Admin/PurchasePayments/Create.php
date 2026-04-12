<?php

namespace App\Livewire\Admin\PurchasePayments;

use App\Models\CashAccount;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class Create extends Component
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
            'purchase_payment.amount' => 'required',
        ];
    }

    function mount()
    {
        $this->purchase_payment = new PurchasePayment();
        $this->purchase_payment->payment_time = Carbon::now()->toDateTimeLocalString();
    }

    function selectSupplier($id)
    {
        $this->purchase_payment->supplier_id = $id;
        $this->supplierSearch = Supplier::find($id)?->name;

        $this->calculateTotalDebt();
    }

    function calculateTotalDebt()
    {
        if (!$this->purchase_payment->supplier_id) return;

        $supplier = Supplier::with('purchases')->find($this->purchase_payment->supplier_id);

        $totalDebt = $supplier->purchases->sum(function ($purchase) {
            return $purchase->total_balance;
        });

        $this->purchase_payment->amount = $totalDebt;
    }

    function savePayment()
    {
        try {
            $this->validate();
            $this->purchase_payment->save();

            // ambil semua yang belum lunas
            $purchases = Purchase::where('supplier_id', $this->purchase_payment->supplier_id)->get();

            foreach ($purchases as $purchase) {
                $remaining = $purchase->total_balance;

                if ($remaining > 0) {
                    $this->purchase_payment->purchases()->attach($purchase->id, [
                        'amount' => $remaining,
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

        return view('livewire.admin.purchase-payments.create', [
            'suppliers' => $suppliers,
            'cashAccounts' => $this->cashAccounts,
        ]);
    }

}

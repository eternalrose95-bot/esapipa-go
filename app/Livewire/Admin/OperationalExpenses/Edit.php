<?php

namespace App\Livewire\Admin\OperationalExpenses;

use App\Models\CashAccount;
use App\Models\OperationalExpense;
use Livewire\Component;

class Edit extends Component
{
    public $operationalExpense;
    public $invoice_number;
    public $date;
    public $store_name;
    public $new_product_name;
    public $new_quantity = 1;
    public $new_unit_price = 0;
    public $new_line_description;
    public $line_items = [];
    public $description;
    public $is_paid = false;
    public $payment_date;
    public $cash_account_id;
    public $cashAccounts;

    public function mount($id)
    {
        $this->operationalExpense = OperationalExpense::findOrFail($id);
        $this->invoice_number = $this->operationalExpense->invoice_number;
        $this->date = $this->operationalExpense->date->format('Y-m-d');
        $this->store_name = $this->operationalExpense->store_name;
        $this->line_items = $this->operationalExpense->line_items ?: [[
            'product_name' => $this->operationalExpense->product_name,
            'quantity' => $this->operationalExpense->quantity,
            'unit_price' => $this->operationalExpense->unit_price,
            'description' => $this->operationalExpense->description,
        ]];
        $this->description = $this->operationalExpense->description;
        $this->is_paid = $this->operationalExpense->is_paid;
        $this->payment_date = $this->operationalExpense->payment_date?->format('Y-m-d');
        $this->cash_account_id = $this->operationalExpense->cash_account_id;
        $this->cashAccounts = CashAccount::all();
    }

    public function addLineItem()
    {
        $this->validate([
            'new_product_name' => 'required|string|min:2',
            'new_quantity' => 'required|numeric|min:1',
            'new_unit_price' => 'required|numeric|min:0',
        ]);

        $this->line_items[] = [
            'product_name' => $this->new_product_name,
            'quantity' => (int) $this->new_quantity,
            'unit_price' => (float) $this->new_unit_price,
            'description' => $this->new_line_description,
        ];

        $this->reset(['new_product_name', 'new_quantity', 'new_unit_price', 'new_line_description']);
    }

    public function removeLineItem($key)
    {
        array_splice($this->line_items, $key, 1);
    }

    public function save()
    {
        $this->validate([
            'invoice_number' => 'required|unique:operational_expenses,invoice_number,' . $this->operationalExpense->id,
            'date' => 'required|date',
            'store_name' => 'required|string|min:3',
            'line_items' => 'required|array|min:1',
            'line_items.*.product_name' => 'required|string|min:2',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.unit_price' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'cash_account_id' => 'nullable|exists:cash_accounts,id',
        ]);

        if ($this->is_paid) {
            if (!$this->cash_account_id) {
                $this->addError('cash_account_id', 'Pilih akun kas untuk pembayaran.');
                return;
            }
            if (!$this->payment_date) {
                $this->addError('payment_date', 'Tanggal pembayaran harus diisi jika sudah lunas.');
                return;
            }
        }

        $this->operationalExpense->update([
            'invoice_number' => $this->invoice_number,
            'date' => $this->date,
            'store_name' => $this->store_name,
            'line_items' => $this->line_items,
            'description' => $this->description,
            'is_paid' => $this->is_paid,
            'payment_date' => $this->is_paid ? $this->payment_date : null,
            'cash_account_id' => $this->is_paid ? $this->cash_account_id : null,
        ]);

        session()->flash('success', 'Belanja operasional berhasil diperbarui.');

        return redirect()->route('admin.operational-expenses.index');
    }

    public function render()
    {
        return view('livewire.admin.operational-expenses.edit');
    }
}

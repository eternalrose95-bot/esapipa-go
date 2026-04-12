<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    protected $fillable = [
        'employee_id',
        'payment_date',
        'amount',
        'cash_account_id',
        'transaction_reference',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function recordCashTransaction()
    {
        CashTransaction::create([
            'cash_account_id' => $this->cash_account_id,
            'transaction_date' => $this->payment_date,
            'amount' => -$this->amount, // outflow
            'type' => 'out',
            'description' => 'Salary payment for ' . $this->employee->name . ' - ' . $this->transaction_reference,
        ]);
    }
}

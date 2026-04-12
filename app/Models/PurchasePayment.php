<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CashAccount;
use App\Models\CashTransaction;

class PurchasePayment extends Model
{
    protected $guarded = [];

    function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_purchase_payment')->withPivot('amount');
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    function transaction()
    {
        return $this->morphOne(CashTransaction::class, 'transactionable');
    }

    public function recordCashTransaction(): void
    {
        if (!$this->cash_account_id || !$this->amount) {
            return;
        }

        $data = [
            'cash_account_id' => $this->cash_account_id,
            'transaction_date' => $this->payment_time,
            'amount' => $this->amount,
            'type' => 'out',
            'description' => 'Pelunasan Belanja: ' . $this->transaction_reference,
        ];

        $transaction = $this->transaction;
        if ($transaction) {
            $transaction->update($data);
        } else {
            $this->transaction()->create($data);
        }
    }
}

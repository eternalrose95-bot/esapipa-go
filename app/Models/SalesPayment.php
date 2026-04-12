<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CashAccount;
use App\Models\CashTransaction;

class SalesPayment extends Model
{
    protected $guarded = [];

    function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_sale_payment')->withPivot(['amount']);
    }

    function client()
    {
        return $this->belongsTo(Client::class);
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
            'type' => 'in',
            'description' => 'Pelunasan Penjualan: ' . $this->transaction_reference,
        ];

        $transaction = $this->transaction;
        if ($transaction) {
            $transaction->update($data);
        } else {
            $this->transaction()->create($data);
        }
    }
}

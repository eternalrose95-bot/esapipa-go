<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function getSignAttribute()
    {
        return $this->type === 'in' ? 1 : -1;
    }
}

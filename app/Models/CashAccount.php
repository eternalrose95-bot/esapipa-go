<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CashTransaction;

class CashAccount extends Model
{
    protected $guarded = [];

    protected $casts = [
        'opening_balance' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function getTotalInAttribute()
    {
        // Optimized: use loaded transactions if available
        if ($this->relationLoaded('transactions')) {
            return $this->transactions->where('type', 'in')->sum('amount');
        }

        // Fallback: calculate from database (more expensive)
        return $this->transactions()->where('type', 'in')->sum('amount');
    }

    public function getTotalOutAttribute()
    {
        // Optimized: use loaded transactions if available
        if ($this->relationLoaded('transactions')) {
            return $this->transactions->where('type', 'out')->sum('amount');
        }

        // Fallback: calculate from database (more expensive)
        return $this->transactions()->where('type', 'out')->sum('amount');
    }

    public function getCurrentBalanceAttribute()
    {
        return $this->opening_balance + $this->total_in - $this->total_out;
    }
}

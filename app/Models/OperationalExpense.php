<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CashAccount;
use App\Models\CashTransaction;

class OperationalExpense extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'payment_date' => 'date',
        'line_items' => 'array',
    ];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function transaction()
    {
        return $this->morphOne(CashTransaction::class, 'transactionable');
    }

    protected static function booted()
    {
        static::saved(function (self $expense) {
            $expense->recordCashTransaction();
        });
    }

    public function recordCashTransaction(): void
    {
        if (!$this->is_paid) {
            $transaction = $this->transaction;
            if ($transaction) {
                $transaction->delete();
            }
            return;
        }

        if (!$this->cash_account_id || !$this->total_amount) {
            return;
        }

        $data = [
            'cash_account_id' => $this->cash_account_id,
            'transaction_date' => $this->payment_date ?? $this->date,
            'amount' => $this->total_amount,
            'type' => 'out',
            'description' => 'Pembayaran Biaya Operasional: ' . $this->invoice_number,
        ];

        $transaction = $this->transaction;
        if ($transaction) {
            $transaction->update($data);
        } else {
            $this->transaction()->create($data);
        }
    }


    public function getTotalAmountAttribute()
    {
        if (is_array($this->line_items) && count($this->line_items)) {
            return collect($this->line_items)->sum(function ($item) {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            });
        }

        return $this->quantity * $this->unit_price;
    }

    public function getProductsSummaryAttribute()
    {
        if (is_array($this->line_items) && count($this->line_items)) {
            return collect($this->line_items)
                ->map(fn ($item) => ($item['product_name'] ?? '-') . ' x' . ($item['quantity'] ?? 0))
                ->join(', ');
        }

        return $this->product_name;
    }

    public function getTotalQuantityAttribute()
    {
        if (is_array($this->line_items) && count($this->line_items)) {
            return collect($this->line_items)->sum(fn ($item) => $item['quantity'] ?? 0);
        }

        return $this->quantity;
    }

    public function getStatusAttribute()
    {
        return $this->is_paid ? 'Lunas' : 'Belum Lunas';
    }
}

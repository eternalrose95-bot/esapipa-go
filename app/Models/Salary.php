<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'base_salary',
        'allowances',
        'bonus',
        'deductions',
        'payday',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTotalSalaryAttribute()
    {
        return $this->base_salary + $this->allowances + $this->bonus - $this->deductions;
    }
}

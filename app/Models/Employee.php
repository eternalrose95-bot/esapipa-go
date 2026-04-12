<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'is_active',
        'attendance_count',
        'leave_count',
        'payday',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'phone',
        'email',
        'nik',
        'join_date',
        'bank_account',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'join_date' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }

    public function getAttendanceCountLastMonthAttribute()
    {
        return $this->attendances()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
    }
}

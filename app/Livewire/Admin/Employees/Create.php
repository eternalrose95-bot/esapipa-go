<?php

namespace App\Livewire\Admin\Employees;

use App\Models\Employee;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $position;
    public $birth_place;
    public $birth_date;
    public $gender;
    public $address;
    public $phone;
    public $email;
    public $nik;
    public $join_date;
    public $bank_account;

    protected $rules = [
        'name' => 'required|string',
        'position' => 'required|string',
        'birth_place' => 'nullable|string',
        'birth_date' => 'nullable|date',
        'gender' => 'nullable|in:male,female',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'email' => 'nullable|email',
        'nik' => 'nullable|string',
        'join_date' => 'nullable|date',
        'bank_account' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();
        Employee::create($this->only([
            'name', 'position', 'birth_place', 'birth_date', 'gender',
            'address', 'phone', 'email', 'nik', 'join_date', 'bank_account'
        ]));
        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully');
    }

    public function render()
    {
        return view('livewire.admin.employees.create');
    }
}
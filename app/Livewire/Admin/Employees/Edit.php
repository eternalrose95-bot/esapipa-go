<?php

namespace App\Livewire\Admin\Employees;

use App\Models\Employee;
use Livewire\Component;

class Edit extends Component
{
    public $employee;
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

    public function mount($employee)
    {
        $this->employee = $employee;
        $this->name = $employee->name;
        $this->position = $employee->position;
        $this->birth_place = $employee->birth_place;
        $this->birth_date = $employee->birth_date?->format('Y-m-d');
        $this->gender = $employee->gender;
        $this->address = $employee->address;
        $this->phone = $employee->phone;
        $this->email = $employee->email;
        $this->nik = $employee->nik;
        $this->join_date = $employee->join_date?->format('Y-m-d');
        $this->bank_account = $employee->bank_account;
    }

    public function save()
    {
        $this->validate();
        $this->employee->update($this->only([
            'name', 'position', 'birth_place', 'birth_date', 'gender',
            'address', 'phone', 'email', 'nik', 'join_date', 'bank_account'
        ]));
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully');
    }

    public function render()
    {
        return view('livewire.admin.employees.edit');
    }
}
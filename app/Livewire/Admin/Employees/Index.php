<?php

namespace App\Livewire\Admin\Employees;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Salary;
use App\Models\SalaryPayment;
use App\Models\CashAccount;
use Livewire\Component;

class Index extends Component
{
    public $showDetailModal = false;
    public $showAttendanceModal = false;
    public $showLeaveModal = false;
    public $showSalaryModal = false;
    public $showAttendanceHistoryModal = false;
    public $showLeaveHistoryModal = false;
    public $showSalaryHistoryModal = false;
    public $showEditPaydayModal = false;
    public $showAddAttendance = false;
    public $showAddLeave = false;
    public $selectedEmployee;
    public $employees;
    public $attendance_date;
    public $attendance_reason;
    public $leave_start;
    public $leave_end;
    public $leave_reason;
    public $payday = 1;
    public $base_salary = 0;
    public $allowances = 0;
    public $bonus = 0;
    public $deductions = 0;
    public $payment_date;
    public $payment_amount;
    public $cash_account_id;
    public $transaction_reference;

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        $this->dispatch('done', success: 'Employee deleted successfully');
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['is_active' => !$employee->is_active]);
        $this->dispatch('done', success: 'Status updated');
    }

    public function showDetail($id)
    {
        $this->selectedEmployee = Employee::find($id);
        $this->showDetailModal = true;
    }

    public function showAttendance()
    {
        $this->showAddAttendance = true;
    }

    public function showLeave()
    {
        $this->showAddLeave = true;
    }

    public function showSalary($id)
    {
        $this->selectedEmployee = Employee::find($id);
        $salary = $this->selectedEmployee->salary;
        if ($salary) {
            $this->payday = $salary->payday;
            $this->base_salary = $salary->base_salary;
            $this->allowances = $salary->allowances;
            $this->bonus = $salary->bonus;
            $this->deductions = $salary->deductions;
        } else {
            $this->payday = 1;
            $this->base_salary = 0;
            $this->allowances = 0;
            $this->bonus = 0;
            $this->deductions = 0;
        }
        $this->showSalaryModal = true;
    }

    public function showAttendanceHistory($id)
    {
        $this->selectedEmployee = Employee::with('attendances')->find($id);
        $this->showAttendanceHistoryModal = true;
    }

    public function showLeaveHistory($id)
    {
        $this->selectedEmployee = Employee::with('leaves')->find($id);
        $this->showLeaveHistoryModal = true;
    }

    public function showSalaryHistory($id)
    {
        $this->selectedEmployee = Employee::with('salaryPayments.cashAccount')->find($id);
        $this->showSalaryHistoryModal = true;
    }

    public function showEditPayday($id)
    {
        $this->selectedEmployee = Employee::with('salary')->find($id);
        $this->payday = $this->selectedEmployee->salary->payday ?? 1;
        $this->showEditPaydayModal = true;
    }

    public function saveAttendance()
    {
        Attendance::create([
            'employee_id' => $this->selectedEmployee->id,
            'date' => $this->attendance_date,
            'reason' => $this->attendance_reason,
        ]);
        $this->selectedEmployee->increment('attendance_count');
        $this->closeModal();
        $this->employees = Employee::with('salary')->get();
        $this->dispatch('done', success: 'Attendance recorded');
    }

    public function saveLeave()
    {
        Leave::create([
            'employee_id' => $this->selectedEmployee->id,
            'start_date' => $this->leave_start,
            'end_date' => $this->leave_end,
            'reason' => $this->leave_reason,
        ]);
        $this->selectedEmployee->increment('leave_count');
        $this->closeModal();
        $this->employees = Employee::with('salary')->get();
        $this->dispatch('done', success: 'Leave recorded');
    }

    public function saveSalary()
    {
        Salary::updateOrCreate(
            ['employee_id' => $this->selectedEmployee->id],
            [
                'payday' => $this->payday,
                'base_salary' => $this->base_salary,
                'allowances' => $this->allowances,
                'bonus' => $this->bonus,
                'deductions' => $this->deductions,
            ]
        );
        $this->selectedEmployee->update(['payday' => $this->payday]);
        $this->closeModal();
        $this->dispatch('done', success: 'Salary updated');
    }

    public function savePayday()
    {
        $this->validate(['payday' => 'required|integer|min:1|max:31']);
        Salary::updateOrCreate(
            ['employee_id' => $this->selectedEmployee->id],
            ['payday' => $this->payday]
        );
        $this->selectedEmployee->update(['payday' => $this->payday]);
        $this->closeModal();
        $this->employees = Employee::with('salary')->get();
        $this->dispatch('done', success: 'Payday updated');
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->showAttendanceModal = false;
        $this->showLeaveModal = false;
        $this->showSalaryModal = false;
        $this->showAttendanceHistoryModal = false;
        $this->showLeaveHistoryModal = false;
        $this->showSalaryHistoryModal = false;
        $this->showEditPaydayModal = false;
        $this->showAddAttendance = false;
        $this->showAddLeave = false;
        $this->selectedEmployee = null;
        $this->resetPaymentForm();
    }

    public function resetPaymentForm()
    {
        $this->payment_date = '';
        $this->payment_amount = 0;
        $this->cash_account_id = null;
        $this->transaction_reference = '';
    }

    public function render()
    {
        $this->employees = Employee::with('salary')->get();
        $cashAccounts = CashAccount::all();
        return view('livewire.admin.employees.index', compact('cashAccounts'));
    }
}
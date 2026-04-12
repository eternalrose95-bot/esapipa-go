<?php

namespace App\Livewire\Admin\Users;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $user = [];
    public $selectedRoles = [];

    function rules()
    {
        return [
            'user.name' => "required",
            'user.email' => "required|email",
            'user.password' => "required|min:8",
            'selectedRoles' => "required",
        ];
    }

    function mount()
    {
        $this->user = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];
    }

    function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    function save()
    {
        $this->validate();
        try {

            $user = User::create($this->user);

            $user->roles()->attach($this->selectedRoles);

            return redirect()->route('admin.users.index');

        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.users.create', [
            'roles' => Role::all()
        ]);
    }
}

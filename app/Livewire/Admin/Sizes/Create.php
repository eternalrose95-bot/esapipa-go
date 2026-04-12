<?php

namespace App\Livewire\Admin\Sizes;

use App\Models\Size;
use Livewire\Component;

class Create extends Component
{
    public Size $size;

    function rules()
    {
        return [
            'size.name' => "required",
        ];
    }

    function mount()
    {
        $this->size = new Size();
    }

    function updated()
    {
        $this->validate();
    }

    function save()
    {
        $this->validate();
        try {
            $this->size->save();
            return redirect()->route('admin.sizes.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.admin.sizes.create');
    }
}

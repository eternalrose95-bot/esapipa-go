<?php

namespace App\Livewire\Admin\Sizes;

use App\Models\Size;
use Livewire\Component;

class Edit extends Component
{
    public $size;

    function rules()
    {
        return [
            'size.name' => "required",
        ];
    }

    function mount($id)
    {
        $this->size = Size::find($id);
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
        return view('livewire.admin.sizes.edit');
    }
}

<?php

namespace App\Livewire\Admin\Sizes;

use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    function delete($id)
    {
        try {
            $size = Size::findOrFail($id);
            if (count($size->products) > 0) {
                throw new \Exception("Error Processing request: This Size has {$size->products->count()} product(s)", 1);
            }
            $size->delete();

            $this->dispatch('done', success: "Successfully Deleted this Size");
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.admin.sizes.index', [
            'sizes' => Size::paginate(10)
        ]);
    }
}

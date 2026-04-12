<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Size;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;
    public $image_file;

    function rules()
    {
        return [
            'product.name' => 'required',
            'product.brand_id' => 'required',
            'product.description' => 'nullable',
            'product.unit_id' => 'required',
            'product.size_id' => 'nullable',
            'product.product_category_id' => 'required',
            'product.purchase_price' => 'required',
            'product.sale_price' => 'required',
            'product.sku' => 'required|unique:products,sku,' . $this->product->id,
            'image_file' => 'nullable|image|max:2048',
        ];
    }

    function mount($id)
    {
        $this->product = Product::find($id);
    }

    function save()
    {
        try {
            $this->validate();

            if ($this->image_file) {
                $this->product->image = $this->image_file->store('products', 'public');
            }

            $this->product->update();

            return redirect()->route('admin.products.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.products.edit', [
            'productCategories' => ProductCategory::all(),
            'units' => Unit::all(),
            'brands' => Brand::all(),
            'sizes' => Size::all(),
        ]);
    }
}

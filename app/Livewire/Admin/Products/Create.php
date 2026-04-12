<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Size;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public Product $product;
    public $margin = 0;
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
            'product.sku' => 'required|unique:products,sku',
            'image_file' => 'nullable|image|max:2048',
        ];
    }

    function mount()
    {
        $this->product = new Product();
        $this->product->sku = $this->generateSKU();
    }

    function updated()
    {
        $this->validate();
    }

    function save()
    {
        try {
            $this->validate();

            if ($this->image_file) {
                $this->product->image = $this->image_file->store('products', 'public');
            }

            $this->product->save();

            return redirect()->route('admin.products.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }

    public function regenerateSKU()
    {
        $this->product->sku = $this->generateSKU();
    }

    private function generateSKU()
    {
        // Ambil SKU terakhir dari database
        $lastProduct = Product::whereNotNull('sku')->latest('id')->first();
        
        if (!$lastProduct || empty($lastProduct->sku)) {
            // Jika belum ada produk, mulai dari 00001
            return '00001';
        }
        
        // Konversi SKU terakhir ke integer dan tambah 1
        $lastSKUNumber = (int) $lastProduct->sku;
        $newSKUNumber = $lastSKUNumber + 1;
        
        // Pastikan tidak melebihi 99999
        if ($newSKUNumber > 99999) {
            $newSKUNumber = 99999;
        }
        
        // Format ke 5 digit dengan leading zeros
        return str_pad($newSKUNumber, 5, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.admin.products.create', [
            'productCategories' => ProductCategory::all(),
            'units' => Unit::all(),
            'brands' => Brand::all(),
            'sizes' => Size::all(),
        ]);
    }

    public function updatedProductPurchasePrice()
    {
        $this->calculateSalePrice();
    }

    public function updatedMargin()
    {
        $this->calculateSalePrice();
    }

    public function calculateSalePrice()
    {
        if ($this->product->purchase_price && $this->margin) {
            $this->product->sale_price =
                $this->product->purchase_price +
                ($this->product->purchase_price * $this->margin / 100);
        }
    }
}


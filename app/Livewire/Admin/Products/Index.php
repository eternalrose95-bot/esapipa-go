<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;

class Index extends Component
{
    use WithPagination, WithFileUploads;
    
    public $search = '';
    public $category_id = '';
    public $excelFile;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    function delete($id)
    {
        try {
            $product = Product::findOrFail($id);
            if (count($product->purchases) > 0 || count($product->sales) > 0) {
                throw new \Exception("Error Processing request: This Product has been bought and/or sold in the system", 1);
            }
            $product->delete();

            $this->dispatch('done', success: "Successfully Deleted this Product");
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }

    public function importProducts()
    {
        $this->validate([
            'excelFile' => 'required|mimes:xlsx,xls|max:10240', // 10MB max
        ]);

        try {
            // Assuming Laravel Excel is installed
            Excel::import(new ProductsImport, $this->excelFile->getRealPath());

            $this->dispatch('done', success: "Produk berhasil diimpor dari Excel");
            $this->excelFile = null;
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Gagal mengimpor: " . $th->getMessage());
        }
    }

    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function render()
    {
        $query = Product::with(['purchases', 'sales', 'category']);

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->category_id)) {
            $query->where('product_category_id', $this->category_id);
        }

        return view('livewire.admin.products.index', [
            'products' => $query->paginate(10),
            'categories' => ProductCategory::all()
        ]);
    }
}


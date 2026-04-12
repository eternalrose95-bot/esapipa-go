<?php

namespace App\Livewire\Admin\ProductCategories;

use App\Models\ProductCategory;
use Livewire\Component;

class Index extends Component
{
    public bool $showProducts = false;
    public ?int $selectedCategoryId = null;
    public string $selectedCategoryName = '';
    public array $selectedCategoryProducts = [];

    function delete($id)
    {
        try {
            $category = ProductCategory::findOrFail($id);
            if (count($category->products) > 0) {
                throw new \Exception("Error Processing request: This Category has {$category->products->count()} products(s)", 1);
            }
            $category->delete();

            $this->dispatch('done', success: "Successfully Deleted this Category");
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }

    public function showProducts($id)
    {
        $category = ProductCategory::with('products')->findOrFail($id);
        $this->selectedCategoryId = $category->id;
        $this->selectedCategoryName = $category->name;
        $this->selectedCategoryProducts = $category->products->map(function ($product) {
            return [
                'name' => $product->name,
                'description' => $product->description,
            ];
        })->toArray();
        $this->showProducts = true;
    }

    public function hideProducts()
    {
        $this->showProducts = false;
        $this->selectedCategoryId = null;
        $this->selectedCategoryName = '';
        $this->selectedCategoryProducts = [];
    }

    public function render()
    {
        $productCategories = ProductCategory::withCount('products')->get();

        $categoryId = request()->query('category_id');
        if ($categoryId) {
            $category = ProductCategory::with('products')->find($categoryId);
            if ($category) {
                $this->showProducts = true;
                $this->selectedCategoryId = $category->id;
                $this->selectedCategoryName = $category->name;
                $this->selectedCategoryProducts = $category->products->map(function ($product) {
                    return [
                        'name' => $product->name,
                        'description' => $product->description,
                    ];
                })->toArray();
            }
        }

        return view('livewire.admin.product-categories.index', [
            'productCategories' => $productCategories,
        ]);
    }
}

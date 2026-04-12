<?php

namespace App\Livewire\Admin\Purchases;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Livewire\Component;

class Create extends Component
{
    public $supplierSearch;
    public $productSearch;
    public $selectedProductName;
    public $selectedProductUnitName;
    public $selectedProductId;
    public $quantity;
    public $price = 0;
    public Purchase $purchase;
    public $discount = 0;
    public $originalPrice = 0;
    public $tax = 0;
    public $notes = '';

    public $productList = [];



    function rules()
    {
        return [
            'purchase.purchase_date' => 'required',
            'purchase.supplier_id' => 'required',
        ];
    }


    function mount()
    {
        $this->purchase = new Purchase();
    }

    function deleteCartItem($key)
    {
        array_splice($this->productList, $key, 1);
    }

    function addQuantity($key)
    {
        $this->productList[$key]['quantity']++;
    }
    function subtractQuantity($key)
    {
        $this->productList[$key]['quantity']--;
    }


    function selectSupplier($id)
    {
        $this->purchase->supplier_id = $id;

        $supplier = Supplier::find($id);
        $this->supplierSearch = $supplier?->name;
    }
    function selectProduct($id)
    {
        $product = Product::with('unit')->find($id);

        if (!$product) return;

            $this->selectedProductId = $product->id;
            $this->productSearch = $product->name;

            $this->originalPrice = $product->purchase_price;
            $this->price = $product->purchase_price;
            
            $this->selectedProductName  = $product->name;
            $this->selectedProductUnitName = $product->unit->name ?? '';
    }
    function addToList()
    {
        try {
            $this->validate([
                'selectedProductId' => 'required',
                'quantity' => 'required',
                'price' => 'required',
            ]);

            foreach ($this->productList as $key => $listItem) {
                if ($listItem['product_id'] == $this->selectedProductId && $listItem['price'] == $this->price) {
                    $this->productList[$key]['quantity'] += $this->quantity;
                    return;
                }
            }



            array_push($this->productList, [
                'product_id' => $this->selectedProductId,
                'product_name' => $this->selectedProductName,
                'original_price' => $this->originalPrice, 
                'unit_name' => $this->selectedProductUnitName,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'discount' => $this->discount,
                'notes' => $this->notes,
            ]);

            $this->reset([
                'productSearch',
                'selectedProductId',
                'quantity',
                'price',
                'discount',
                'originalPrice',
                'notes',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }

    function makePurchase()
    {

        try {
            $this->validate();

            $subtotal = collect($this->productList)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            $taxAmount = $subtotal * ($this->tax / 100);
            $grandTotal = $subtotal + $taxAmount;

            if ($this->purchase->supplier_id) {
                $supplier = $this->purchase->supplier ?? Supplier::find($this->purchase->supplier_id);
            }

            $this->purchase->subtotal = $subtotal;
            $this->purchase->tax_percentage = $this->tax;
            $this->purchase->tax_amount = $taxAmount;
            $this->purchase->grand_total = $grandTotal;

            $this->purchase->save();
            foreach ($this->productList as $key => $listItem) {
                $this->purchase->products()->attach($listItem['product_id'], [
                    'quantity' => $listItem['quantity'],
                    'unit_price' => $listItem['price'],
                    'discount_percentage' => $listItem['discount'],
                    'notes' => $listItem['notes'],
                ]);
            }

            return redirect()->route('admin.purchases.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->supplierSearch . '%')->get();
        $products = Product::with('purchases', 'sales')->where('name', 'like', '%' . $this->productSearch . '%')->get();

        return view('livewire.admin.purchases.create', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }

    public function updatedDiscount()
    {
        $this->calculateFinalPrice();
    }

    public function calculateFinalPrice()
    {
        if ($this->originalPrice) {
            $this->price = $this->originalPrice -
                ($this->originalPrice * $this->discount / 100);
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->productList)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    public function getTaxAmountProperty()
    {
        $subtotal = (float) $this->subtotal;
        $tax = (float) $this->tax;

        return $subtotal * ($tax / 100);
    }

    public function getGrandTotalProperty()
    {
        return (float) $this->subtotal + (float) $this->taxAmount;
    }
}

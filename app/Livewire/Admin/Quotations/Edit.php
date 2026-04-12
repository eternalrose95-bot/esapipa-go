<?php

namespace App\Livewire\Admin\Quotations;

use App\Models\Product;
use App\Models\Quotation;
use Livewire\Component;

class Edit extends Component
{
    public $productSearch;
    public $selectedProductId;
    public $quantity;
    public $price;
    public $discount = 0;
    public $originalPrice = 0;
    public $tax = 0;
    public $notes = '';
    public Quotation $quotation;

    public $productList = [];



    function rules()
    {
        return [
            'quotation.quotation_date' => 'required',
            'quotation.client_name' => 'required|string',
        ];
    }


    function mount($id)
    {
        $this->quotation = Quotation::find($id);

        foreach ($this->quotation->products as $key => $product) {
            $unit_price = $product->pivot->unit_price;
            $discount = $product->pivot->discount_percentage ?? 0;
            $price = $unit_price - ($unit_price * $discount / 100);

            array_push($this->productList, [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_name' => $product->unit?->name,
                'quantity' => $product->pivot->quantity,
                'price' => $price,
                'original_price' => $unit_price,
                'discount' => $discount,
                'notes' => $product->pivot->notes ?? '',
            ]);
        }
        $this->tax = $this->quotation->tax_percentage ?? 0;
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


    function selectProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return;
        }

        $this->selectedProductId = $product->id;
        $this->productSearch = $product->name;
        $this->originalPrice = $product->sale_price;
        $this->price = $product->sale_price;
        $this->discount = 0;
    }
    function addToList()
    {
        try {
            $this->validate([
                'selectedProductId' => 'required',
                'quantity' => 'required',
            ]);

            $product = Product::find($this->selectedProductId);
            if (!$product) {
                throw new \Exception('Selected product not found', 1);
            }

            foreach ($this->productList as $key => $listItem) {
                if ($listItem['product_id'] == $this->selectedProductId && $listItem['price'] == $this->price && ($listItem['discount'] ?? 0) == $this->discount) {
                    $this->productList[$key]['quantity'] += $this->quantity;
                    return;
                }
            }

            array_push($this->productList, [
                'product_id' => $this->selectedProductId,
                'product_name' => $product->name,
                'unit_name' => $product->unit?->name,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'original_price' => $this->originalPrice,
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

    function makeQuotation()
    {

        try {
            $this->validate();

            if (count($this->productList) === 0) {
                throw new \Exception("Produk belum ditambahkan", 1);
            }

            $subtotal = collect($this->productList)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            $taxAmount = round($subtotal * ($this->tax / 100));
            $grandTotal = $subtotal + $taxAmount;

            $this->quotation->subtotal = round($subtotal);
            $this->quotation->tax_percentage = $this->tax;
            $this->quotation->tax_amount = $taxAmount;
            $this->quotation->grand_total = $grandTotal;

            $this->quotation->save();
            $this->quotation->products()->detach();
            foreach ($this->productList as $key => $listItem) {
                $this->quotation->products()->attach($listItem['product_id'], [
                    'quantity' => $listItem['quantity'],
                    'unit_price' => $listItem['price'],
                    'discount_percentage' => $listItem['discount'] ?? 0,
                    'notes' => $listItem['notes'],
                ]);
            }
            return redirect()->route('admin.quotations.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        $products = Product::with('purchases', 'sales')->where('name', 'like', '%' . $this->productSearch . '%')->get();

        return view('livewire.admin.quotations.edit', [
            'products' => $products,
        ]);
    }

    public function updatedDiscount()
    {
        $this->calculateFinalPrice();
    }

    public function updatedProductList()
    {
        foreach ($this->productList as $key => $item) {
            $this->productList[$key]['price'] = $item['original_price'] - ($item['original_price'] * ($item['discount'] ?? 0) / 100);
        }
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

        return round($subtotal * ($tax / 100));
    }

    public function getGrandTotalProperty()
    {
        return (float) $this->subtotal + (float) $this->taxAmount;
    }
}

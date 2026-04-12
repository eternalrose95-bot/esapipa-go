<?php

namespace App\Livewire\Admin\Sales;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;

class Create extends Component
{
    public $clientSearch;
    public $productSearch;
    public $selectedProductId;
    public $quantity;
    public $price;
    public $discount = 0;
    public $originalPrice = 0;
    public $tax = 0;
    public $notes = '';
    public Sale $sale;

    public $productList = [];



    function rules()
    {
        return [
            'sale.sale_date' => 'required',
            'sale.client_id' => 'required',
        ];
    }


    function mount()
    {
        $this->sale = new Sale();
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


    function selectClient($id)
    {
        $this->sale->client_id = $id;
        $this->clientSearch = $this->sale->client->name;

    }
    function selectProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return;
        }

        $this->selectedProductId = $product->id;
        $this->productSearch = $product->name;
        $this->originalPrice = $product->purchase_price;
        $this->price = $product->purchase_price;
        $this->discount = 0;
    }
    function addToList()
    {
        try {
            $this->validate([
                'selectedProductId' => 'required',
                'quantity' => 'required',
                'price' => 'required',
            ]);

            if (Product::find($this->selectedProductId)->inventory_balance < $this->quantity) {
                throw new \Exception("Inventory Balance is Low", 1);
            }
            foreach ($this->productList as $key => $listItem) {
                if ($listItem['product_id'] == $this->selectedProductId && $listItem['price'] == $this->price && ($listItem['discount'] ?? 0) == $this->discount) {
                    $this->productList[$key]['quantity'] += $this->quantity;
                    return;
                }
            }

            array_push($this->productList, [
                'product_id' => $this->selectedProductId,
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

    function makeSale()
    {

        try {
            $this->validate();

            if (count($this->productList) === 0) {
                throw new \Exception("Produk belum ditambahkan", 1);
            }

            foreach ($this->productList as $key => $listItem) {
                if (Product::find($listItem['product_id'])->inventory_balance < $listItem['quantity']) {
                    throw new \Exception("Inventory Balance for " . Product::find($listItem['product_id'])->name . " is Low", 1);
                }
            }

            $subtotal = collect($this->productList)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            $taxAmount = $subtotal * ($this->tax / 100);
            $grandTotal = $subtotal + $taxAmount;

            $this->sale->subtotal = $subtotal;
            $this->sale->tax_percentage = $this->tax;
            $this->sale->tax_amount = $taxAmount;
            $this->sale->grand_total = $grandTotal;

            $this->sale->save();

            foreach ($this->productList as $listItem) {
                $this->sale->products()->attach($listItem['product_id'], [
                    'quantity' => $listItem['quantity'],
                    'unit_price' => $listItem['price'],
                    'discount_percentage' => $listItem['discount'] ?? 0,
                    'notes' => $listItem['notes'],
                ]);
            }

            return redirect()->route('admin.sales.index');

        }
        
        catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
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

    public function render()
    {
        $clients = Client::where('name', 'like', '%' . $this->clientSearch . '%')->get();
        $products = Product::with('purchases', 'sales')->where('name', 'like', '%' . $this->productSearch . '%')->get();

        return view('livewire.admin.sales.create', [
            'clients' => $clients,
            'products' => $products,
        ]);
    }
}

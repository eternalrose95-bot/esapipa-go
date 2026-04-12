<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Livewire\Component;

class Edit extends Component
{
    public $supplierSearch;
    public $productSearch;
    public $selectedProductId;
    public $quantity;
    public $notes = '';
    public Order $order;

    public $productList = [];

    function rules()
    {
        return [
            'order.order_date' => 'required',
            'order.supplier_id' => 'required',
            'order.notes' => 'nullable|string',
        ];
    }

    function mount($id)
    {
        $this->order = Order::find($id);

        foreach ($this->order->products as $product) {
            array_push($this->productList, [
                'product_id' => $product->id,
                'quantity' => $product->pivot->quantity,
                'notes' => $product->pivot->notes,
            ]);
        }

        $this->supplierSearch = $this->order->supplier->name;
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
        $this->order->supplier_id = $id;
        $this->supplierSearch = $this->order->supplier->name;
    }

    function selectProduct($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return;
        }

        $this->selectedProductId = $id;
        $this->productSearch = $product->name;
        $this->price = $product->purchase_price;
    }

    function addToList()
    {
        try {
            $this->validate([
                'selectedProductId' => 'required',
                'quantity' => 'required',
            ]);

            foreach ($this->productList as $key => $listItem) {
                if ($listItem['product_id'] == $this->selectedProductId) {
                    $this->productList[$key]['quantity'] += $this->quantity;
                    return;
                }
            }

            array_push($this->productList, [
                'product_id' => $this->selectedProductId,
                'quantity' => $this->quantity,
                'price' => $this->price ?? Product::find($this->selectedProductId)->purchase_price,
                'notes' => $this->notes,
            ]);

            $this->reset([
                'productSearch',
                'selectedProductId',
                'quantity',
                'notes',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }

    function makeOrder()
    {
        try {
            $this->validate();
            $this->order->save();
            $this->order->products()->detach();
            foreach ($this->productList as $listItem) {
                $this->order->products()->attach($listItem['product_id'], [
                    'quantity' => $listItem['quantity'],
                    'unit_price' => $listItem['price'] ?? Product::find($listItem['product_id'])->purchase_price,
                    'notes' => $listItem['notes'],
                ]);
            }
            return redirect()->route('admin.orders.index');
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something Went Wrong: " . $th->getMessage());
        }
    }

    public function render()
    {
        $suppliers = Supplier::where('name', 'like', '%' . $this->supplierSearch . '%')->get();
        $products = Product::with('purchases', 'sales')->where('name', 'like', '%' . $this->productSearch . '%')->get();

        return view('livewire.admin.orders.edit', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}

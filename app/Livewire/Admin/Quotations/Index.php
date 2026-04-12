<?php

namespace App\Livewire\Admin\Quotations;

use App\Models\Quotation;
use App\Models\Sale;
use App\Models\Client;
use Livewire\Component;

class Index extends Component
{
    function delete($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            // if ($quotation->is_paid) {
            //     throw new \Exception("Error Processing request: This Quotation has already been paid for", 1);
            // }

            $quotation->products()->detach();
            $quotation->delete();

            $this->dispatch('done', success: "Successfully Deleted this Quotation");
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }

    function convertToSale($id)
    {
        try {
            $quotation = Quotation::with('products')->findOrFail($id);

            // Find client by name
            $client = Client::where('name', $quotation->client_name)->first();
            if (!$client) {
                throw new \Exception("Client '{$quotation->client_name}' not found. Please create the client first.");
            }

            // Create new sale
            $sale = Sale::create([
                'client_id' => $client->id,
                'sale_date' => now()->format('Y-m-d'),
                'subtotal' => $quotation->subtotal,
                'tax_percentage' => $quotation->tax_percentage,
                'tax_amount' => $quotation->tax_amount,
                'grand_total' => $quotation->grand_total,
            ]);

            // Attach products to sale
            foreach ($quotation->products as $product) {
                $sale->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'unit_price' => $product->pivot->unit_price,
                    'discount_percentage' => $product->pivot->discount_percentage,
                    'notes' => $product->pivot->notes,
                ]);
            }

            // Delete the quotation
            $quotation->products()->detach();
            $quotation->delete();

            $this->dispatch('done', success: "Quotation successfully converted to Sale");
        } catch (\Throwable $th) {
            $this->dispatch('done', error: "Something went wrong: " . $th->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.quotations.index', [
            'quotations' => Quotation::all()
        ]);
    }
}

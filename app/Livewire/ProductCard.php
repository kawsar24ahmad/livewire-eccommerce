<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\ProductVariant;

class ProductCard extends Component
{
    public Product $product;
    public function addToCart()
    {
        if ($this->product->stock_status != 'in_stock') {
            session()->flash('error', $this->product->name . ' is not in stock');
            return;
        }

        $cart = session()->get('cart', []);
        $cartKey = 'product_' . $this->product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {

            $cart[$cartKey] = [
                'product_id' => $this->product->id,
                'variant_id' => null,
                'name' => $this->product->name,
                'variant_name' => null,
                'price' => $this->product->price,
                'image' => $this->product->primaryImage?->image_path,
                'quantity' => 1,
            ];
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added successfully');
    }
    public function render()
    {

        return view('livewire.product-card');
    }
}

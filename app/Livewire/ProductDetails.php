<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;
use Pest\Support\NullClosure;

class ProductDetails extends Component
{
    public Product $product;
    public $selectedImage = null;

    public $quantity = 1;
    public $selectedVariant = null;
    public function incrementQuantity(){
        $this->quantity++;
    }
    public function selectImage($image_path){
        $this->selectedImage = $image_path;

    }
    public function selectVariant($variantId){
        $this->selectedVariant = $variantId;
    }
    public function decrementQuantity(){
        $this->quantity--;

    }
    public function addToCart(){
        if ($this->product->has_variants && !$this->selectedVariant) {
            session()->flash('error', 'Please Select a variant!');
        }
        $cart = session()->get('cart', []);
        $cartKey = $this->selectedVariant ? 'variant_'. $this->selectedVariant : 'product_' . $this->product->id;

        if (isset($cart[$cartKey])) {
           $cart[$cartKey]['quantity'] += $this->quantity;
        }else{
            if ($this->selectedVariant) {
                $variant = ProductVariant::find($this->selectedVariant);
                $cart[$cartKey] = [
                    'product_id' => $this->product->id,
                    'variant_id' => $variant->id,
                    'name' => $this->product->name,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'image' => $this->selectedImage,
                    'quantity' => $this->quantity,
                ];
            }else{
                $cart[$cartKey] = [
                    'product_id' => $this->product->id,
                    'variant_id' => null,
                    'name' => $this->product->name,
                    'variant_name' => null,
                    'price' => $this->product->price,
                    'image' => $this->selectedImage,
                    'quantity' => $this->quantity,
                ];
            }
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added successfully');
    }
    public function mount($slug){

        $this->product = Product::active()->where('slug', $slug)
        ->with(['category', 'brand', 'images', 'approvedReviews.customer', 'variants'])->firstOrFail();
        // dd($product);
        $this->selectedImage = $this->product->primaryImage?->image_path ?? $this->product->images()->first()?->iamage_path;

        if ( $this->product->has_variants && $this->product->variants->isNotEmpty()) {
            $this->selectedVariant = $this->product->variants()->first()->id;
        }
    }
    public function render()
    {
        $relatedProducts = Product::active()
        ->where('category_id', $this->product->category->id)
        ->where('id', '!=', $this->product->id)
        ->with(['primaryImage'])
        ->limit(4)
        ->get();
        return view('livewire.product-details',[
            'relatedProducts' =>$relatedProducts
        ])->layout('components.layouts.frontend');
    }
}

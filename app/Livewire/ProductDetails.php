<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Livewire\Component;

class ProductDetails extends Component
{
    public Product $product;

    public ?string $selectedImage = null;
    public ?int $selectedVariant = null;
    public ?int $selectedSize = null;
    public int $quantity = 1;

    // public function mount($slug): void
    // {
    //     $this->product = Product::active()
    //         ->where('slug', $slug)
    //         ->with([
    //             'category',
    //             'brand',
    //             'images',
    //             'approvedReviews.customer',
    //             'variants.color',
    //             'variants.size',
    //         ])
    //         ->firstOrFail();

    //     $this->product->increment('views_count');

    //     // Pick initial image: primary image → first image
    //     $this->selectedImage = $this->product->primaryImage?->image_path
    //         ?? $this->product->images->first()?->image_path;

    //     // Auto-select first available variant for the price/gallery
    //     $first = $this->product->variants->where('is_active', true)->first();
    //     if ($first) {
    //         $this->selectVariant($first->id);
    //     }
    // }

    public function mount($slug): void
    {
        $this->product = Product::active()
            ->where('slug', $slug)
            ->with([
                'category',
                'brand',
                'images',
                'approvedReviews.customer',
                'variants.color',
                'variants.size',
            ])
            ->firstOrFail();

        $this->product->increment('views_count');
        // dd($this->product->primaryImage?->image_path);

        // Pick initial image: primary image → first image
        $this->selectedImage = $this->product->primaryImage?->image_path
            ?? $this->product->images->first()?->image_path;

        // Auto-select first available variant for the price/gallery
        $first = $this->product->variants->where('is_active', true)->first();
        if ($first) {
            $this->selectVariant($first->id);
        }
    }

    public function selectVariant(int $variantId): void
    {
        $variant = $this->product->variants->find($variantId);
        if (! $variant) {
            return;
        }

        $this->selectedVariant = $variant->id;
        $this->selectedSize = $variant->size_id;

        // Swap the gallery to the variant's image, fall back to primary product image
        if ($variant->image_path) {
            $this->selectedImage = $variant->image_path;
        } else {
            $this->selectedImage = $this->product->primaryImage?->image_path
                ?? $this->product->images->first()?->image_path;
        }
    }

    public function selectSize(int $sizeId): void
    {
        $this->selectedSize = $sizeId;
        // Find a matching variant for this size
        $variant = $this->product->variants
            ->where('size_id', $sizeId)
            ->where('is_active', true)
            ->first();
        if ($variant) {
            $this->selectVariant($variant->id);
        }
    }

    public function selectImage(string $imagePath): void
    {
        $this->selectedImage = $imagePath;
    }

    public function incrementQuantity(): void
    {
        $this->quantity++;
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    private function handleCartLogic(): bool
    {
        if ($this->product->has_variants && ! $this->selectedVariant) {
            session()->flash('error', 'Please select a size and color.');
            return false;
        }

        $cart = session()->get('cart', []);
        $cartKey = $this->selectedVariant
            ? 'variant_' . $this->selectedVariant
            : 'product_' . $this->product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            $base = [
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
                'image' => $this->selectedImage,
            ];

            if ($this->selectedVariant) {
                $variant = ProductVariant::with(['color', 'size'])->find($this->selectedVariant);
                $cart[$cartKey] = $base + [
                    'variant_id' => $variant->id,
                    'name' => $this->product->name,
                    'variant_name' => $variant->display_label,
                    'color' => $variant->color?->name,
                    'size' => $variant->size?->name,
                    'price' => (float) $variant->price,
                ];
            } else {
                $cart[$cartKey] = $base + [
                    'variant_id' => null,
                    'name' => $this->product->name,
                    'variant_name' => null,
                    'color' => null,
                    'size' => $this->product->sizes->firstWhere('id', $this->selectedSize)?->name,
                    'price' => (float) $this->product->price,
                ];
            }
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        return true;
    }

    public function addToCart(): void
    {
        if ($this->handleCartLogic()) {
            session()->flash('success', 'Product added to cart.');
        }
    }

    public function buyNow(): void
    {
        if ($this->handleCartLogic()) {
            // Redirects the user directly to the cart page
            // Change 'cart' to whatever your actual route name or URL is
            $this->redirect(route('cart.index'), navigate: true);
        }
    }

    // public function addToCart(): void
    // {
    //     if ($this->product->has_variants && ! $this->selectedVariant) {
    //         session()->flash('error', 'Please select a size and color.');
    //         return;
    //     }

    //     $cart = session()->get('cart', []);
    //     $cartKey = $this->selectedVariant
    //         ? 'variant_' . $this->selectedVariant
    //         : 'product_' . $this->product->id;

    //     if (isset($cart[$cartKey])) {
    //         $cart[$cartKey]['quantity'] += $this->quantity;
    //     } else {
    //         $base = [
    //             'product_id' => $this->product->id,
    //             'quantity' => $this->quantity,
    //             'image' => $this->selectedImage,
    //         ];

    //         if ($this->selectedVariant) {
    //             $variant = ProductVariant::with(['color', 'size'])->find($this->selectedVariant);
    //             $cart[$cartKey] = $base + [
    //                 'variant_id' => $variant->id,
    //                 'name' => $this->product->name,
    //                 'variant_name' => $variant->display_label,
    //                 'color' => $variant->color?->name,
    //                 'size' => $variant->size?->name,
    //                 'price' => (float) $variant->price,
    //             ];
    //         } else {
    //             $cart[$cartKey] = $base + [
    //                 'variant_id' => null,
    //                 'name' => $this->product->name,
    //                 'variant_name' => null,
    //                 'color' => null,
    //                 'size' => $this->product->sizes->firstWhere('id', $this->selectedSize)?->name,
    //                 'price' => (float) $this->product->price,
    //             ];
    //         }
    //     }

    //     session()->put('cart', $cart);
    //     $this->dispatch('cart-updated');
    //     session()->flash('success', 'Product added to cart.');
    // }
    // public function buyNow(): void
    // {
    //     if ($this->product->has_variants && ! $this->selectedVariant) {
    //         session()->flash('error', 'Please select a size and color.');
    //         return;
    //     }

    //     $cart = session()->get('cart', []);
    //     $cartKey = $this->selectedVariant
    //         ? 'variant_' . $this->selectedVariant
    //         : 'product_' . $this->product->id;

    //     if (isset($cart[$cartKey])) {
    //         $cart[$cartKey]['quantity'] += $this->quantity;
    //     } else {
    //         $base = [
    //             'product_id' => $this->product->id,
    //             'quantity' => $this->quantity,
    //             'image' => $this->selectedImage,
    //         ];

    //         if ($this->selectedVariant) {
    //             $variant = ProductVariant::with(['color', 'size'])->find($this->selectedVariant);
    //             $cart[$cartKey] = $base + [
    //                 'variant_id' => $variant->id,
    //                 'name' => $this->product->name,
    //                 'variant_name' => $variant->display_label,
    //                 'color' => $variant->color?->name,
    //                 'size' => $variant->size?->name,
    //                 'price' => (float) $variant->price,
    //             ];
    //         } else {
    //             $cart[$cartKey] = $base + [
    //                 'variant_id' => null,
    //                 'name' => $this->product->name,
    //                 'variant_name' => null,
    //                 'color' => null,
    //                 'size' => $this->product->sizes->firstWhere('id', $this->selectedSize)?->name,
    //                 'price' => (float) $this->product->price,
    //             ];
    //         }
    //     }

    //     session()->put('cart', $cart);
    //     $this->dispatch('cart-updated');
    //     session()->flash('success', 'Product added to cart.');
    // }

    public function render()
    {
        $relatedProducts = Product::active()
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with(['primaryImage'])
            ->limit(4)
            ->get();

        return view('livewire.product-details', [
            'relatedProducts' => $relatedProducts,
        ])->layout('components.layouts.frontend');
    }
}

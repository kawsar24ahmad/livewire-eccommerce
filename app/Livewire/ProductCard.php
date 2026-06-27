<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\ProductVariant;

class ProductCard extends Component
{
    public Product $product;

    public bool $showVariantModal = false;
    public bool $buyNowAction = false;

    public ?int $selectedVariant = null;
    public int $quantity = 1;

    /**
     * Ensure variants are loaded with their color/size relationships
     * so the modal can render without N+1 queries.
     */
    public function mount(): void
    {
        $this->product->loadMissing([
            'primaryImage',
            'variants.color',
            'variants.size',
        ]);
    }

    /* -----------------------------------------------------------------
     |  Entry points invoked from the card buttons
     | ----------------------------------------------------------------- */

    public function addToCartClicked(): void
    {
        $this->openModal(buyNow: false);
    }

    public function buyNowClicked(): void
    {
        $this->openModal(buyNow: true);
    }
    public function updatedQuantity($value)
    {
        $this->quantity = max(1, (int) $value);
    }

    protected function openModal(bool $buyNow): void
    {
        $this->buyNowAction = $buyNow;
        $this->quantity = 1;

        /*
    |--------------------------------------------------------------------------
    | Simple Product
    |--------------------------------------------------------------------------
    */

        if (! $this->product->has_variants) {

            if ($this->handleCartLogic()) {

                if ($buyNow) {
                    $this->redirect(route('cart.index'), navigate: true);
                }
            }

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Variant Product
    |--------------------------------------------------------------------------
    */

        $first = $this->product->variants
            ->where('is_active', true)
            ->sortBy('sort_order')
            ->first();

        $this->selectedVariant = $first?->id;

        $this->showVariantModal = true;
    }

    public function closeModal(): void
    {
        $this->reset([
            'showVariantModal',
            'buyNowAction',
            'selectedVariant',
        ]);

        $this->quantity = 1;
    }
    public function getCurrentVariantProperty()
    {
        return $this->product
            ->variants
            ->firstWhere('id', $this->selectedVariant);
    }

    /* -----------------------------------------------------------------
     |  Variant + quantity state
     | ----------------------------------------------------------------- */

    public function selectVariant(int $variantId): void
    {
        $variant = $this->product
            ->variants
            ->firstWhere('id', $variantId);

        if (! $variant) {
            return;
        }

        if (! $variant->is_active) {
            return;
        }

        $this->selectedVariant = $variant->id;
    }

    public function incrementQuantity(): void
    {
        if ($this->quantity < 99) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    /* -----------------------------------------------------------------
     |  Confirm action from inside the modal
     | ----------------------------------------------------------------- */

    public function confirmVariant(): void
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:99',
            'selectedVariant' => 'required|integer',
        ]);

        if ($this->handleCartLogic()) {
            // 1. Cache the flag or check it before resetting state
            $shouldRedirect = $this->buyNowAction;

            // 2. Clear out the modal state
            $this->closeModal();

            // 3. Perform redirect based on the cached flag
            if ($shouldRedirect) {
                $this->redirect(route('cart.index'), navigate: true);
            }
        }
    }

    /* -----------------------------------------------------------------
     |  Cart persistence (works for both simple and variant products)
     | ----------------------------------------------------------------- */

    private function handleCartLogic(): bool
    {
        if ($this->product->stock_status !== 'in_stock') {
            session()->flash('error', $this->product->name . ' is out of stock.');
            return false;
        }

        $cart = session()->get('cart', []);

        if ($this->product->has_variants) {
            $variant = $this->product
                ->variants
                ->firstWhere('id', $this->selectedVariant);

            if (! $variant) {
                session()->flash(
                    'error',
                    'Selected variant is unavailable.'
                );

                return false;
            }

            $cartKey = 'variant_' . $variant->id;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $this->quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id'    => $this->product->id,
                    'variant_id'    => $variant->id,
                    'name'          => $this->product->name,
                    'variant_name'  => $variant->display_label,
                    'color'         => $variant->color?->name,
                    'size'          => $variant->size?->name,
                    'price'         => (float) $variant->price,
                    'image'         => $variant->image_path
                        ?: $this->product->primaryImage?->image_path,
                    'quantity'      => $this->quantity,
                ];
            }
        } else {
            $cartKey = 'product_' . $this->product->id;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $this->quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id'   => $this->product->id,
                    'variant_id'   => null,
                    'name'         => $this->product->name,
                    'variant_name' => null,
                    'color'        => null,
                    'size'         => null,
                    'price'        => (float) $this->product->price,
                    'image'        => $this->product->primaryImage?->image_path,
                    'quantity'     => $this->quantity,
                ];
            }
        }

        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
        session()->flash('success', $this->product->name . ' added to cart.');
        return true;
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}

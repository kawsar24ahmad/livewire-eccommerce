<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Homepage extends Component
{
    public function render()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'brand', 'primaryImage'])
            ->limit(8)
            ->get();
        $newArrivals = Product::active()
            ->with(['category', 'brand', 'primaryImage'])
            ->latest()
            ->limit(8)
            ->get();
        $categories = Category::active()
            ->sorted()
            ->withCount('products')
            ->limit(6)
            ->get();

        return view('livewire.homepage', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'newArrivals' => $newArrivals
        ])->layout('components.layouts.frontend');
    }
}

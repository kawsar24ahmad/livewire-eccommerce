<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListing extends Component
{
    use WithPagination;
    #[Url()]
    public $search = '';
    #[Url()]
    public $maxPrice = '';
    #[Url()]
    public $minPrice = '';
    #[Url()]
    public $category = '';
    #[Url()]
    public $brand = '';
    #[Url()]
    public $sort = 'newest';
    #[Url()]
    public $priceRange = [0, 10000];
    #[Url()]
    public $featured = '';


    public function clearFilters()
    {
        $this->reset([
            'search',
            'category',
            'brand',
            'maxPrice',
            'minPrice',
            'sort',
            'priceRange',
            'featured'
        ]);
        $this->maxPrice = $this->priceRange[1];
        $this->resetPage();
    }
    public function updatingSearch(){
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingBrand()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }
    public function applyPriceFilter()
    {

        $this->resetPage();
    }
    public function mount()
    {
        $maxProductPrice = Product::active()->max('price') ?? 10000;
        $this->priceRange = [0, ceil($maxProductPrice)];

        if (empty($this->maxPrice)) {
            $this->maxPrice = $this->priceRange[1];
        }
    }

    public function render()
    {
        $query = Product::query()
            ->active()
            ->with(['category', 'brand', 'primaryImage']);

        if ($this->search) {
            $query->where('name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('description', 'LIKE', '%' . $this->search . '%')
                ->orWhere('sku', 'LIKE', '%' . $this->search . '%');
        }
        if ($this->category) {
            $categoryModel = Category::where('slug', $this->category)->first();
            if ($categoryModel) {
                $query->where('category_id', $categoryModel->id);
            }
        }
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel) {
                $query->where('brand_id', $brandModel->id);
            }
        }
        if ($this->maxPrice !== '' || $this->maxPrice !== '') {
            $min = $this->minPrice ?: 0;
            $max = $this->maxPrice ?: $this->priceRange[1];
            $query->whereBetween('price', [$min, $max]);
        }
        if ($this->featured) {
            $query->featured();
        }

        match ($this->sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest()
        };
        $products =  $query->paginate(10);

        $categories = Category::active()->sorted()->withCount('products')->get();

        $brands = Brand::active()->sorted()->withCount('products')->get();
// dd($products);

        return view('livewire.product-listing', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ])->layout('components.layouts.frontend');
    }
}

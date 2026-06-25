<?php

namespace App\Models;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'manage_stock',
        'stock_status',
        'is_active',
        'is_featured',
        'has_variants',
        'weight',
        'meta_title',
        'meta_description',
        'sort_order',
        'views_cont',
    ];
    protected function casts()
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'views_count' => 'integer',
            'manage_stock' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'has_variants' => 'boolean',
        ];
    }
    #[Scope]
    protected function active(Builder $builder)
    {
        $builder->where('is_active', true);
    }
    #[Scope]
    protected function featured(Builder $builder)
    {
        $builder->where('is_featured', true);
    }
    #[Scope]
    protected function inStock(Builder $builder)
    {
        $builder->where('stock_status', 'in_stock')
            ->where('stock_quantity', '>', 0);
    }
    #[Scope]
    protected function lowStock(Builder $builder)
    {
        return $builder->where('stock_status', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0);
    }
    #[Scope]
    protected function inCategory(Builder $builder, int $categoryId)
    {
        $builder->where('category_id', $categoryId);
    }
    #[Scope]
    protected function ofBrand(Builder $builder, int $brandId)
    {
        $builder->where('brand', $brandId);
    }
    #[Scope]
    protected function inPriceRange(Builder $builder, float $min, float $max)
    {
        $builder->whereBetween('price', [$min, $max]);
    }
    // relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }
    // helper methods
    public function getDiscountPercentageAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }
    public function getReviewsCountAttribute()
    {
        return $this->approvedReviews()->count();
    }
    public function incrementViews()
    {
        $this->increment('views_count');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . Str::random(8);
            }
        });
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}

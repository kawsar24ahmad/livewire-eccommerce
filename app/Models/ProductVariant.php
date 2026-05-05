<?php

namespace App\Models;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'options',
        'price',
        'compare_price',
        'stock_quantity',
        'stock_status',
        'is_active',
        'sort_order',
    ];
    protected function casts(){
        return [
            'options' => 'array',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'stock_quantity' => 'integer',
             'sort_order' => 'integer',
        ];
    }
    #[Scope]
    protected function active(Builder $builder){
        $builder->where('is_active', true);
    }
    #[Scope]
    protected function inStock(Builder $builder){
        $builder->where('stock_status', 'in_stock')
            ->where('stock_quantity', '>', 0);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function images(){
        return $this->hasMany(ProductImage::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
    // helper methods
    public function getDiscountPercentageAttribute()  {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }
    protected static function boot()  {
        parent::boot();
        static::creating(function($variant){
            if (empty($variant->sku)) {
                $variant->sku = 'SKU-'. Str::random(8);
            }
        });
    }
}

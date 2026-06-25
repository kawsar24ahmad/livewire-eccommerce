<?php

namespace App\Models;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'name',
        'sku',
        'price',
        'compare_price',
        'image_path',
        'stock_quantity',
        'stock_status',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    #[Scope]
    protected function active(Builder $builder): void
    {
        $builder->where('is_active', true);
    }

    #[Scope]
    protected function inStock(Builder $builder): void
    {
        $builder->where('stock_status', 'in_stock')
            ->where('stock_quantity', '>', 0);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getDisplayLabelAttribute(): string
    {
        $parts = array_filter([$this->color?->name, $this->size?->name]);
        return $parts ? implode(' / ', $parts) : ($this->name ?? 'Variant');
    }

    public function getDiscountPercentageAttribute(): int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku = 'VAR-' . Str::random(8);
            }
        });
        static::saving(function ($variant) {
            // Auto-generate a human-friendly name from color + size
            $variant->name = $variant->display_label;
        });
    }
}

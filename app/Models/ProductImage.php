<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'image_path',
        'alt_text',
        'is_primary',
        'sort_order',
    ];
    protected function casts(){
        return [
            'is_primary' => 'boolean',
             'sort_order' => 'integer',
        ];
    }
    #[Scope]
    protected function primary(Builder $builder){
        $builder->where('is_primary', true);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function variant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function getUrlAttribute(){
        return asset('storage/'. $this->image_path);
    }


}

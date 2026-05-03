<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
}

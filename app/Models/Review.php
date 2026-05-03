<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'customer_id',
        'order_id',
        'rating',
        'title',
        'content',
        'is_verified_purchase',
        'is_approved',
    ];
}

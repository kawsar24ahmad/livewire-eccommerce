<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order_value',
        'minimum_discount',
        'usage_limit',
        'usage_limit_per_customer',
        'starts_at',
        'expires_at',
        'is_active',
   ];
}

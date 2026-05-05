<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
    ];
    public function coupon(){
        return $this->belongsTo(Coupon::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}

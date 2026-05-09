<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
        use HasFactory;
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
    protected function casts(){
        return [
            'rating' => 'integer',
            'is_verified_purchase' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }
     #[Scope]
    protected function approved(Builder $builder){
        $builder->where('is_approved', true);
    }
     #[Scope]
    protected function verified(Builder $builder){
        $builder->where('is_verified_purchase', true);
    }
     #[Scope]
    protected function rating(Builder $builder, int $rating){
        $builder->where('rating', $rating);
    }
    // relations
     public function product(){
        return $this->belongsTo(Product::class);
    }
     public function customer(){
        return $this->belongsTo(Customer::class);
    }
     public function order(){
        return $this->belongsTo(Order::class);
    }
}

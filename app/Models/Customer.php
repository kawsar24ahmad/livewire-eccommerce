<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Review;
use App\Models\Address;
use App\Models\CouponUsage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Customer extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'is_active',
        'remember_token'
    ];

    protected $hidden = [
         'password',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    #[Scope()]
    protected  function active(Builder $builder)  {
        $builder->where('is_active', true);
    }

    public function addresses()  {
        return $this->hasMany(Address::class);
    }
    public function defaultAddress()  {
        return $this->hasOne(Address::class)->where('is_default', true);
    }
    public function orders()  {
        return $this->hasMany(Order::class);
    }
    public function reviews()  {
        return $this->hasMany(Review::class);
    }
    public function couponUsages()  {
        return $this->hasMany(CouponUsage::class);
    }

    public function getTotalSpentAttribute()  {
        return $this->orders()->where('payment_status', 'paid')->sum('total');
    }
    public function getOrderCountAttribute()  {
        return $this->orders()->count();
    }

}

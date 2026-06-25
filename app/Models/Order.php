<?php

namespace App\Models;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_number',
        'customer_id',
        'coupon_id',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'tax_amount',
        'total',
        'shipping_full_name',
        'shipping_phone',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'is_default',
        'type',
        'payment_method',
        'payment_status',
        'transaction_id',
        'status',
        'tracking_number',
        'customer_notes',
        'admin_notes',
    ];
    #[Scope]
    public function ofStatus(Builder $builder, string $status)
    {
        return $builder->where('status', $status);
    }
    #[Scope]
    public function paymentStatus(Builder $builder, string $status)
    {
        return $builder->where('payment_status', $status);
    }
    #[Scope]
    public function pending(Builder $builder)
    {
        return $builder->where('status', 'pending');
    }
    #[Scope]
    public function processing(Builder $builder)
    {
        return $builder->where('status', 'processing');
    }
    public function shipped(Builder $builder)
    {
        return $builder->where('status', 'shipped');
    }
    public function delivered(Builder $builder)
    {
        return $builder->where('status', 'delivered');
    }
    public function cancelled(Builder $builder)
    {
        return $builder->where('status', 'cancelled');
    }

    public function getShippingAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->shipping_address_line_1,
            $this->shipping_address_line_2,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_postal_code,
            $this->shipping_country
        ]));
    }
    public function updateStatus($newStatus, $notes = null, $userId = null)
    {
        $this->update([
            'status' => $newStatus,
        ]);
        $this->orderStatuses()->create([
            'status' => $newStatus,
            'notes' => $notes,
            'user_id' => $userId
        ]);
    }
    // relations
    public function orderStatuses()
    {
        return $this->hasMany(OrderStatus::class)->orderBy('created_at', 'desc');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
        static::created(function ($order) {
            $order->orderStatuses()->create([
                'status' => $order->status,
                'notes' => 'Order Created',
                // 'user_id' => $order->customer_id
            ]);
            // order confirmation email
        });
        static::updated(function ($order) {
            $order->orderStatuses()->create([
                'status' => $order->status,
                'notes' => 'Order Updated',
                // 'user_id' => $order->customer_id
            ]);
            // order confirmation email
        });
    }
}

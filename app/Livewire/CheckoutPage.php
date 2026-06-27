<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Setting;
use Livewire\Component;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutPage extends Component
{
    public $cart = [];

    // Address fields
    public $useExistingAddress = false;
    public $selectedAddressId = null;
    public $full_name = '';
    public $phone = '';
    public $address_line_1 = '';
    public $shipping_area = 'inside_dhaka';

    public $customerNotes = '';
    public $paymentMethod = 'stripe';

    public $couponCode = '';
    public $appliedCoupon = null;

    public function getSubtotal()
    {
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $this->cart));
    }

    public function getShippingCost()
    {
        if ($this->shipping_area === 'inside_dhaka') {
            return Setting::get('shipping_cost_inside_dhaka', 60);
        }
        return Setting::get('shipping_cost_outside_dhaka', 120);
    }

    public function validateAddress()
    {
        if (!$this->useExistingAddress) {
            $this->validate([
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'address_line_1' => 'required|string|max:255',
                'shipping_area' => 'required|string|in:inside_dhaka,outside_dhaka',
            ]);
        } else {
            if (!$this->selectedAddressId) {
                throw new \Exception('Please select a delivery address.');
            }
        }
    }

    public function mount()
    {
        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            return redirect()->route('cart.index');
        }

        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $this->full_name = $customer->name ?? '';
            $this->phone = $customer->phone ?? '';

            $defaultAddress = $customer->addresses()->where('is_default', true)->first();
            if ($defaultAddress) {
                $this->useExistingAddress = true;
                $this->selectedAddressId = $defaultAddress->id;
            } elseif ($customer->addresses()->count() > 0) {
                $this->useExistingAddress = true;
                $this->selectedAddressId = $customer->addresses()->first()->id;
            }
        }
    }

    public function placeOrder()
    {
        try {
            $this->validateAddress();

            DB::beginTransaction();

            $customerId = null;

            // 1. Structure the shipping data consistently
            $shippingData = [
                'shipping_full_name' => $this->useExistingAddress && $this->selectedAddressId && auth("customer")->check()
                    ? Address::find($this->selectedAddressId)->full_name
                    : $this->full_name,
                'shipping_phone' => $this->useExistingAddress && $this->selectedAddressId && auth("customer")->check()
                    ? Address::find($this->selectedAddressId)->phone
                    : $this->phone,
                'shipping_address_line_1' => $this->useExistingAddress && $this->selectedAddressId && auth("customer")->check()
                    ? Address::find($this->selectedAddressId)->address_line_1
                    : $this->address_line_1,
            ];

            // 2. Handle Logged-in vs Guest Customer Logic
            if (auth("customer")->check()) {
                $customerId = auth('customer')->id();

                if (!$this->useExistingAddress || !$this->selectedAddressId) {
                    $customer = auth("customer")->user();
                    $customer->addresses()->where('is_default', 1)->update(['is_default' => 0]);
                    $customer->addresses()->create([
                        'full_name' => $this->full_name,
                        'phone' => $this->phone,
                        'address_line_1' => $this->address_line_1,
                        'is_default' => 1,
                    ]);
                }
            } else {
                // Create Guest Customer account
                $customer = Customer::create([
                    'name' => $this->full_name,
                    'phone' => $this->phone,
                    'is_active' => 0,
                ]);

                $customerId = $customer->id; // Assign the newly created guest ID to the order

                $customer->addresses()->create([
                    'full_name' => $this->full_name,
                    'phone' => $this->phone,
                    'address_line_1' => $this->address_line_1,
                    'is_default' => 1,
                ]);
            }

            // 3. Calculate Totals
            $subtotal = $this->getSubtotal();
            $shippingCost = $this->getShippingCost();
            $discountAmount = $this->getDiscountAmount();
            $taxAmount = 0;
            $total = $subtotal + $shippingCost + $taxAmount - $discountAmount;

            // 4. Create Order (Using the verified $customerId)
            $order = Order::create([
                'customer_id' => $customerId,
                'coupon_id' => $this->appliedCoupon?->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'pending',
                'status' => 'pending',
                'customer_notes' => $this->customerNotes,
            ] + $shippingData);

            // 5. Create Order Items (Optimized SKU fetching)
            foreach ($this->cart as $item) {
                $sku = $item['sku'] ?? null;

                if (!$sku) {
                    $sku = ($item['variant_id'] ?? null)
                        ? \App\Models\ProductVariant::where('id', $item['variant_id'])->value('sku')
                        : \App\Models\Product::where('id', $item['product_id'])->value('sku');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['name'],
                    'product_sku' => $sku,
                    'variant_name' => $item['variant_name'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // 6. Apply Coupons (Only if registered user; optional based on business rules)
            if ($this->appliedCoupon && auth('customer')->check()) {
                $this->appliedCoupon->usages()->create([
                    'customer_id' => $customerId,
                    'order_id' => $order->id,
                ]);
            }

            DB::commit();

            if ($this->paymentMethod === 'stripe') {
                return $this->processStripePayment($order);
            } else {
                session()->forget('cart');

                if (auth('customer')->check()) {
                    // Registered customers go to their account dashboard
                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('success', 'Order placed successfully!');
                } else {
                    // Guests go to a public thank you page, storing the order ID temporarily in the session
                    return redirect()->route('order.success')
                        ->with('completed_order_id', $order->id);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', "Error: {$th->getMessage()}");
            return;
        }
    }

    public function applyCoupon()
    {
        $coupon = Coupon::where('code', strtoupper($this->couponCode))->valid()->first();

        if (!$coupon) {
            session()->flash('coupon_error', 'Invalid or expired coupon code');
            return;
        }

        if (!$coupon->canBeUsedByCustomer(auth('customer')->id())) {
            session()->flash('coupon_error', 'You have already used this coupon');
            return;
        }

        $this->appliedCoupon = $coupon;
        session()->flash('coupon_success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->appliedCoupon = null;
    }

    public function getDiscountAmount()
    {
        if (!$this->appliedCoupon) {
            return 0;
        }
        return $this->appliedCoupon->calculateDiscount($this->getSubtotal());
    }

    public function render()
    {
        $addresses = collect();
        if (auth('customer')->check()) {
            $addresses = auth('customer')->user()->addresses;
        }

        return view('livewire.checkout-page', [
            'addresses' => $addresses,
            'subtotal' => $this->getSubtotal(),
            'shippingCost' => $this->getShippingCost(),
            'discountAmount' => $this->getDiscountAmount(),
            'total' => $this->getSubtotal() + $this->getShippingCost() - $this->getDiscountAmount(),
        ])->layout('components.layouts.frontend');
    }
}

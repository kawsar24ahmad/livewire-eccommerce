<div class="bg-slate-50 min-h-screen py-12 antialiased">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="mb-8 border-b border-gray-200 pb-5">
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Secure Checkout</h1>
            <p class="mt-2 text-sm text-slate-500">Please review your address details and preferred selection to
                complete your purchase.</p>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-8 lg:items-start">

            <div class="lg:col-span-7 space-y-6">

                @if (session()->has('error'))
                    <div class="rounded-lg bg-red-50 p-4 border border-red-200 flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 overflow-hidden">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">1</span>
                            Shipping Destination
                        </h2>

                        @if ($addresses->count() > 0)
                            <button type="button" wire:click="$toggle('useExistingAddress')"
                                class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                                {{ $useExistingAddress ? 'Ship to a new address' : 'Use saved address' }}
                            </button>
                        @endif
                    </div>

                    @if($useExistingAddress && $addresses->count() > 0)
                        <div class="grid gap-4">
                            @foreach($addresses as $address)
                                <label class="relative block cursor-pointer group">
                                    <input type="radio" wire:model="selectedAddressId" value="{{ $address->id }}"
                                        class="peer sr-only">
                                    <div
                                        class="rounded-xl border border-slate-200 p-4 transition-all group-hover:border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-50/40 peer-checked:ring-2 peer-checked:ring-blue-600/10">
                                        <div class="flex items-start justify-between">
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-slate-800">{{ $address->full_name }}</p>
                                                    @if($address->is_default)
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 border border-slate-200">Default</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-slate-500 font-medium">{{ $address->phone }}</p>
                                                <p class="text-sm text-slate-600 pt-1 leading-relaxed">
                                                    {{ $address->address_line_1 }}
                                                </p>
                                            </div>
                                            <div class="flex h-5 items-center">
                                                <div
                                                    class="h-4 w-4 rounded-full border border-slate-300 bg-white flex items-center justify-center peer-checked:border-blue-600">
                                                    <div class="h-2 w-2 rounded-full bg-blue-600 hidden peer-checked:block">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Full
                                    Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="full_name"
                                    class="w-full px-3.5 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-sm">
                                @error('full_name') <span
                                class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Phone
                                    Number <span class="text-red-500">*</span></label>
                                <input type="tel" wire:model="phone"
                                    class="w-full px-3.5 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-sm">
                                @error('phone') <span
                                class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Complete
                                    Delivery Address <span class="text-red-500">*</span></label>
                                <textarea wire:model="address_line_1" rows="3"
                                    placeholder="Apartment, suite, unit, building, street address details..."
                                    class="w-full px-3.5 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-sm"></textarea>
                                @error('address_line_1') <span
                                class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-5">
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">2</span>
                        Delivery Region
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="shipping_area" value="inside_dhaka"
                                class="peer sr-only">
                            <div
                                class="border border-slate-200 rounded-xl p-4 transition peer-checked:border-blue-600 peer-checked:bg-blue-50/40 group-hover:border-slate-300 flex items-center gap-3">
                                <div
                                    class="p-2 rounded-lg bg-slate-50 text-slate-600 peer-checked:bg-blue-100 peer-checked:text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">Inside Dhaka</p>
                                    <p class="text-xs text-slate-500 font-medium">Fast local delivery standard</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="shipping_area" value="outside_dhaka"
                                class="peer sr-only">
                            <div
                                class="border border-slate-200 rounded-xl p-4 transition peer-checked:border-blue-600 peer-checked:bg-blue-50/40 group-hover:border-slate-300 flex items-center gap-3">
                                <div
                                    class="p-2 rounded-lg bg-slate-50 text-slate-600 peer-checked:bg-blue-100 peer-checked:text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">Outside Dhaka</p>
                                    <p class="text-xs text-slate-500 font-medium">Standard country-wide courier</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-5">
                        <span
                            class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600">3</span>
                        Payment Method
                    </h2>

                    <div class="space-y-3">
                        <label class="relative block cursor-pointer group">
                            <input type="radio" wire:model.live="paymentMethod" value="stripe" class="peer sr-only">
                            <div
                                class="border border-slate-200 rounded-xl p-4 transition peer-checked:border-blue-600 peer-checked:bg-blue-50/40 group-hover:border-slate-300 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-lg bg-slate-50 text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">Credit or Debit Card</p>
                                        <p class="text-xs text-slate-500 font-medium">Processed securely through Stripe
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative block cursor-pointer group">
                            <input type="radio" wire:model.model="paymentMethod" value="cash_on_delivery"
                                class="peer sr-only">
                            <div
                                class="border border-slate-200 rounded-xl p-4 transition peer-checked:border-blue-600 peer-checked:bg-blue-50/40 group-hover:border-slate-300 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-lg bg-slate-50 text-slate-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">Cash on Delivery (COD)</p>
                                        <p class="text-xs text-slate-500 font-medium">Pay with raw cash inside delivery
                                            envelope</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Order
                            Notes (Optional)</label>
                        <textarea wire:model="customerNotes" rows="2"
                            placeholder="Notes for fulfillment handlers or delivery runners..."
                            class="w-full px-3.5 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors text-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-10 lg:mt-0 lg:col-span-5 lg:sticky lg:top-8">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4">Order Summary</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Items Subtotal</span>
                            <span class="font-semibold text-slate-800">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Shipping Fee</span>
                            <span class="font-semibold text-slate-800">
                                @if($shippingCost > 0)
                                    ${{ number_format($shippingCost, 2) }}
                                @else
                                    <span class="text-emerald-600 font-bold uppercase tracking-wider text-xs">Free
                                        Shipping</span>
                                @endif
                            </span>
                        </div>
                        @if($discountAmount > 0)
                            <div
                                class="flex justify-between text-sm bg-emerald-50/50 rounded-lg p-2 border border-emerald-100">
                                <span class="text-emerald-700 font-medium">Coupon Savings</span>
                                <span class="font-bold text-emerald-700">-${{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        @if(!$appliedCoupon)
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Have a promo
                                coupon?</label>
                            <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="GIFT20"
                                    class="flex-1 px-3.5 py-1.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition text-sm uppercase font-mono tracking-wider">
                                <button type="button" wire:click="applyCoupon"
                                    class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800 transition shadow-sm">
                                    Apply
                                </button>
                            </div>
                            @if (session()->has('coupon_error'))
                                <p class="text-red-500 text-xs mt-1.5 font-medium flex items-center gap-1">✕
                                    {{ session('coupon_error') }}
                                </p>
                            @endif
                        @else
                            <div
                                class="flex items-center justify-between bg-emerald-50 rounded-xl p-3 border border-emerald-200">
                                <div class="flex items-center gap-2">
                                    <div class="bg-emerald-500 text-white p-1 rounded-md">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-800 uppercase font-mono tracking-wide">
                                            Active: {{ $appliedCoupon->code }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="removeCoupon"
                                    class="text-xs text-red-600 hover:text-red-700 font-semibold uppercase tracking-wider">
                                    Remove
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-base font-bold text-slate-900 block">Grand Total</span>
                                <span class="text-xs text-slate-400">All local taxes included</span>
                            </div>
                            <span class="text-3xl font-black text-blue-600 tracking-tight">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>

                    <button type="button" wire:click="placeOrder" wire:loading.attr="disabled"
                        class="w-full bg-blue-600 text-white text-center py-4 rounded-xl font-bold hover:bg-blue-700 transition shadow-md focus:ring-4 focus:ring-blue-500/20 flex items-center justify-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove wire:target="placeOrder">
                            Confirm Order
                        </span>
                        <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                            <svg class="animate-spin  h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span>Processing Order Request...</span>
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
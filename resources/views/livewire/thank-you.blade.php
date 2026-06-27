<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center bg-white p-8 rounded-lg shadow-sm border border-gray-100 mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                Thank you for your order!
            </h1>
            <p class="mt-3 text-lg text-gray-500">
                Your order <span class="font-semibold text-gray-800">#{{ $order->order_number }}</span> has been placed
                successfully.
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="mt-2 sm:mt-0">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 capitalize">
                        Payment: {{ $order->payment_status }}
                    </span>
                </div>
            </div>

            <div class="px-6 divide-y divide-gray-100">
                @foreach($order->items as $item)
                    <div class="py-6 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h3>
                            @if($item->variant_name)
                                <p class="mt-1 text-xs text-gray-500">Variant: {{ $item->variant_name }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">Qty: {{ $item->quantity }} ×
                                ${{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-sm font-medium text-gray-900 ml-4">
                            ${{ number_format($item->subtotal, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 px-6 py-6 border-t border-gray-100 space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>

                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Discount</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-sm text-gray-600">
                    <span>Shipping Cost</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>

                <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total Paid</span>
                    <span>${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Delivery Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-1">Shipping Address</h4>
                    <p class="text-gray-600">{{ $order->shipping_full_name }}</p>
                    <p class="text-gray-600">{{ $order->shipping_address_line_1 }}</p>
                    <p class="text-gray-600">Phone: {{ $order->shipping_phone }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-1">Payment Method</h4>
                    <p class="text-gray-600 uppercase">{{ $order->payment_method }}</p>

                    @if($order->customer_notes)
                        <h4 class="font-semibold text-gray-700 mt-4 mb-1">Your Notes</h4>
                        <p class="text-gray-600 italic">"{{ $order->customer_notes }}"</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="/" wire:navigate
                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                Continue Shopping
            </a>
        </div>

    </div>
</div>
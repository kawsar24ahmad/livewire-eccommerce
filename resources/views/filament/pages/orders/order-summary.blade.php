<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

    <div class="space-y-3 mb-6">

        <div class="flex justify-between">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">
                {{ number_format($getRecord()->subtotal, 2) }} BDT
            </span>
        </div>

        @if($getRecord()->discount_amount > 0)
            <div class="flex justify-between text-green-600">
                <span>Discount</span>
                <span class="font-medium">
                    -{{ number_format($getRecord()->discount_amount, 2) }} BDT
                </span>
            </div>
        @endif

        <div class="flex justify-between">
            <span class="text-gray-600">Shipping</span>
            <span class="font-medium">
                @if($getRecord()->shipping_cost > 0)
                    {{ number_format($getRecord()->shipping_cost, 2) }} BDT
                @else
                    <span class="text-green-600">FREE</span>
                @endif
            </span>
        </div>

        @if($getRecord()->tax_amount > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Tax</span>
                <span class="font-medium">
                    {{ number_format($getRecord()->tax_amount, 2) }} BDT
                </span>
            </div>
        @endif
    </div>

    <div class="border-t pt-4 mb-6">
        <div class="flex justify-between items-center">
            <span class="text-lg font-semibold">Total</span>
            <span class="text-2xl font-bold text-blue-600">
                {{ number_format($getRecord()->total, 2) }} BDT
            </span>
        </div>
    </div>

    @if($getRecord()->customer_notes)
        <div class="border-t pt-4">
            <p class="text-sm font-medium text-gray-900 mb-2">Order Notes</p>
            <p class="text-sm text-gray-600">
                {{ $getRecord()->customer_notes }}
            </p>
        </div>
    @endif
</div>
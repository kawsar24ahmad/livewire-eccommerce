<div class="group relative bg-white rounded-lg shadow-sm hover:shadow-lg transition duration-300 overflow-hidden">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        {{-- Product Image --}}
        <div class="aspect-square overflow-hidden bg-gray-200">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                    <span class="text-6xl text-gray-500">{{ substr($product->name, 0, 1) }}</span>
                </div>
            @endif
        </div>

        {{-- Badges --}}
        <div class="absolute top-2 left-2 flex flex-col gap-2">
            @if($product->is_featured)
                <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded">Featured</span>
            @endif
            @if($product->discount_percentage > 0)
                <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
            @if($product->stock_status === 'out_of_stock')
                <span class="bg-gray-800 text-white text-xs font-semibold px-2 py-1 rounded">Out of Stock</span>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="p-4">
            <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition">
                {{ $product->name }}
            </h3>

            @if($product->reviews_count > 0)
                <div class="flex items-center gap-1 mb-2">
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product->average_rating))
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @else
                                <svg class="w-4 h-4 fill-current text-gray-300" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-xs text-gray-600">({{ $product->reviews_count }})</span>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <span class="text-xl font-bold text-gray-900">
                    ${{ number_format($product->price, 2) }}
                </span>
                @if($product->compare_price)
                    <span class="text-sm text-gray-500 line-through">
                        ${{ number_format($product->compare_price, 2) }}
                    </span>
                @endif
            </div>
        </div>
    </a>

    {{-- ====================================================== --}}
    {{-- Action buttons --}}
    {{-- ====================================================== --}}
    @if($product->stock_status === 'in_stock')
        <div class="flex gap-1">
            <div class="p-1 pt-0 w-full">
                <button wire:click="addToCartClicked"
                    class="w-full cursor-pointer bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition font-medium">
                    Add to Cart
                </button>
            </div>
            <div class="p-1 pt-0 w-full">
                <button wire:click="buyNowClicked"
                    class="w-full cursor-pointer bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition font-medium">
                    Buy Now
                </button>
            </div>
        </div>
    @else
        <div class="p-4 pt-0">
            <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed font-medium">
                Out of Stock
            </button>
        </div>
    @endif

    {{-- ====================================================== --}}
    {{-- Variant Modal --}}
    {{-- ====================================================== --}}
    @if($showVariantModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" wire:click.self="closeModal">

            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">

                {{-- Header --}}
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ $buyNowAction ? 'Buy Now' : 'Select Variant' }}
                    </h2>
                    <button wire:click="closeModal" class="text-2xl text-gray-500 hover:text-gray-800 leading-none">
                        ✕
                    </button>
                </div>

                {{-- Body --}}
                <div class="overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-5 gap-6">

                    {{-- LEFT: Product basic info --}}
                    @php
                        $currentVariant = $selectedVariant
                            ? $product->variants->find($selectedVariant)
                            : null;

                        $displayImage = $currentVariant?->image_path
                            ?: $product->primaryImage?->image_path;

                        $displayPrice = $currentVariant?->price ?? $product->price;
                        $displayCompare = $currentVariant?->compare_price ?? $product->compare_price;

                        $variantTitle = $currentVariant?->display_label;
                    @endphp

                    <div class="md:col-span-2">
                        <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 mb-4">
                            @if($displayImage)
                                <img src="{{ asset('storage/' . $displayImage) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                    <span class="text-5xl text-gray-500">{{ substr($product->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-xs text-gray-500 mb-1">
                            {{ $product->category->name ?? '' }}
                            @if($product->brand)
                                • {{ $product->brand->name }}
                            @endif
                        </p>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $product->name }}
                        </h3>

                        @if($variantTitle)
                            <p class="text-sm text-blue-600 font-medium mb-3">
                                {{ $variantTitle }}
                            </p>
                        @endif

                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl font-bold text-gray-900">
                                ${{ number_format($displayPrice, 2) }}
                            </span>
                            @if($displayCompare && $displayCompare > $displayPrice)
                                <span class="text-sm text-gray-500 line-through">
                                    ${{ number_format($displayCompare, 2) }}
                                </span>
                            @endif
                        </div>

                        @if($product->short_description)
                            <p class="text-sm text-gray-600 mt-2 line-clamp-3">
                                {{ $product->short_description }}
                            </p>
                        @endif

                        @if($currentVariant?->sku)
                            <p class="text-xs text-gray-400 mt-3">
                                SKU: {{ $currentVariant->sku }}
                            </p>
                        @endif
                    </div>

                    {{-- RIGHT: Variant grid + qty + actions --}}
                    <div class="md:col-span-3 flex flex-col">

                        <h4 class="text-sm font-semibold text-gray-900 mb-3">
                            Available Options
                        </h4>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5 max-h-[40vh] overflow-y-auto pr-1">
                            @foreach($product->variants->where('is_active', true) as $item)
                                            @php
                                                $variantTitle = collect([
                                                    $item->color?->name,
                                                    $item->size?->name,
                                                ])->filter()->implode(' • ');

                                                $variantTitle = $variantTitle ?: 'Standard Product';
                                            @endphp

                                            <button type="button" wire:click="selectVariant({{ $item->id }})" class="border rounded-xl overflow-hidden text-left transition bg-white
                                                                                                            {{ $selectedVariant == $item->id
                                ? 'ring-2 ring-blue-500 border-blue-500 shadow-md'
                                : 'border-gray-200 hover:border-blue-300 hover:shadow' }}">

                                                @if($item->image_path)
                                                    <div class="aspect-square bg-gray-100 overflow-hidden">
                                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $variantTitle }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                @endif

                                                <div class="p-2.5">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        @if($item->color)
                                                            <span class="w-3 h-3 rounded-full border border-gray-300"
                                                                style="background-color: {{ $item->color->hex_code ?? '#ddd' }}">
                                                            </span>
                                                        @endif
                                                        <span class="text-xs font-semibold text-gray-900 line-clamp-1">
                                                            {{ $variantTitle }}
                                                        </span>
                                                    </div>

                                                </div>
                                            </button>
                            @endforeach
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-900 mb-2">Quantity</label>
                            <div class="flex items-center gap-3">
                                <button wire:click="decrementQuantity" type="button"
                                    class="w-9 h-9 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                </button>
                                <input type="number" wire:model="quantity" min="1"
                                    class="w-20 text-center border border-gray-300 rounded-lg py-2">
                                <button wire:click="incrementQuantity" type="button"
                                    class="w-9 h-9 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Footer actions --}}
                        <div class="flex gap-3 mt-auto pt-4 border-t">
                            <button wire:click="closeModal" type="button"
                                class="flex-1 px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Cancel
                            </button>
                            <button wire:click="confirmVariant" type="button" @disabled(!$selectedVariant) class="flex-1 px-5 py-2.5 rounded-lg font-medium transition
                                                        {{ $selectedVariant
            ? 'bg-blue-600 text-white hover:bg-indigo-700'
            : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                                {{ $buyNowAction ? 'Buy Now' : 'Add to Cart' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
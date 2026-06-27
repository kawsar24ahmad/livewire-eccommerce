<div class="bg-gray-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600">Home</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-blue-600">Shop</a></li>
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                        class="text-gray-500 hover:text-blue-600">{{ $product->category->name }}</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 p-8">

                <!-- ================= Images ================= -->
                <div>
                    <div x-data="{ hover:false, x:50, y:50 }" @mouseenter="hover = true" @mouseleave="hover = false"
                        @mousemove="
                            const rect = $el.getBoundingClientRect();
                            x = (($event.clientX - rect.left) / rect.width) * 100;
                            y = (($event.clientY - rect.top) / rect.height) * 100;
                         " class="aspect-square overflow-hidden rounded-xl cursor-zoom-in bg-gray-100">
                        <img src="{{ $selectedImage ? asset('storage/' . $selectedImage) : 'https://via.placeholder.com/600' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-200" :style="hover
                                ? `transform: scale(2.5); transform-origin: ${x}% ${y}%`
                                : 'transform: scale(1)'">
                    </div>

                    @php
                        $variantImagePath = $selectedVariant
                            ? $product->variants->find($selectedVariant)?->image_path
                            : null;

                        if ($variantImagePath) {
                            // Convert Eloquent Collection to a base Collection so we can push a stdClass
                            // without triggering Eloquent's getKey() duplicate detection.
                            $gallery = $product->images->toBase()->push((object) [
                                'image_path' => $variantImagePath,
                                'is_primary' => true,
                            ]);
                        } else {
                            $gallery = $product->images;
                        }
                    @endphp

                    @if($gallery->count() > 1)
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            @foreach($gallery as $image)
                                <button wire:click="selectImage('{{ $image->image_path }}')"
                                    class="aspect-square rounded-lg overflow-hidden border-2 transition
                                                                                                                                                                                    {{ $selectedImage === $image->image_path ? 'border-blue-600' : 'border-gray-200 hover:border-indigo-400' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ================= Product Info ================= -->
                <div>
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($product->is_featured)
                            <span
                                class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded">Featured</span>
                        @endif
                        <span class="bg-{{ $product->stock_status === 'in_stock' ? 'green' : 'red' }}-100
                                     text-{{ $product->stock_status === 'in_stock' ? 'green' : 'red' }}-800
                                     text-sm font-semibold px-3 py-1 rounded">
                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                        </span>
                    </div>

                    @if($product->brand)
                        <p class="text-sm text-gray-500 mb-2">{{ $product->brand->name }}</p>
                    @endif

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                    @if($product->reviews_count > 0)
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product->average_rating))
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-600">{{ number_format($product->average_rating, 1) }}
                                ({{ $product->reviews_count }} reviews)</span>
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="mb-6">
                        @php $variant = $selectedVariant ? $product->variants->find($selectedVariant) : null; @endphp
                        @if($variant)
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-3xl font-bold text-gray-900">${{ number_format($variant->price, 2) }}</span>
                                @if($variant->compare_price)
                                    <span
                                        class="text-xl text-gray-500 line-through">${{ number_format($variant->compare_price, 2) }}</span>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">
                                        -{{ $variant->discount_percentage }}%
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @if($product->compare_price)
                                    <span
                                        class="text-xl text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-semibold">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($product->short_description)
                        <p class="text-gray-600 mb-6">{{ $product->short_description }}</p>
                    @endif

                    @php
                        $sizeGroups = $product->variants
                            ->where('is_active', true)
                            ->filter(fn($v) => $v->size_id)
                            ->groupBy('size_id');

                        $offeredSizes = $sizeGroups->map(fn($g) => $g->first()->size);
                        $sizeGroups = $product->variants
                            ->where('is_active', true)
                            ->filter(fn($v) => $v->size_id)
                            ->groupBy('size_id');
                    @endphp
                    @if($offeredSizes->whereNotNull('chest')->isNotEmpty() || $offeredSizes->whereNotNull('length')->isNotEmpty())
                        <div class="my-6 overflow-x-auto">
                            <div class="rounded-xl border border-gray-200 shadow-sm bg-white">
                                <div class="bg-blue-500 text-white text-center py-3 font-semibold rounded-t-xl">Size Chart
                                </div>
                                <table class="w-full text-sm text-center border-collapse">
                                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                                        <tr>
                                            <th class="p-3 text-left bg-gray-100">SIZE</th>
                                            @foreach($offeredSizes as $size)
                                                <th class="p-3">{{ $size->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <tr>
                                            <th class="p-3 text-left font-medium text-gray-700 bg-gray-100">CHEST</th>
                                            @foreach($offeredSizes as $size)
                                                <td class="p-3">{{ $size->chest ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th class="p-3 text-left font-medium text-gray-700 bg-gray-100">LENGTH</th>
                                            @foreach($offeredSizes as $size)
                                                <td class="p-3">{{ $size->length ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($product->has_variants && $product->variants->where('is_active', true)->count())
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Available Options
                            </h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($product->variants->where('is_active', true) as $item)
                                                    @php
                                                        $variantTitle = collect([
                                                            $item->color?->name,
                                                            $item->size?->name,
                                                        ])->filter()->implode(' • ');
                                                        $variantTitle = $variantTitle ?: 'Standard Product';
                                                    @endphp

                                                    <button type="button" wire:click="selectVariant({{ $item->id }})" class="group relative border rounded-xl overflow-hidden bg-white text-left transition-all duration-300 hover:shadow-lg hover:-translate-y-1
                                                                            {{ $selectedVariant == $item->id
                                    ? 'ring-2 ring-blue-500 border-blue-500 shadow-lg'
                                    : 'border-gray-200 hover:border-blue-300' }}">

                                                        @if($item->image_path)
                                                            <div class="aspect-square bg-gray-100 overflow-hidden">
                                                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $variantTitle }}"
                                                                    class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                                                            </div>
                                                        @endif

                                                        <div class="p-3">
                                                            <div class="flex items-center gap-2 mb-1">
                                                                @if($item->color)
                                                                    <span class="w-4 h-4 rounded-full border border-gray-300"
                                                                        style="background-color: {{ $item->color->hex_code ?? '#ddd' }}">
                                                                    </span>
                                                                @endif
                                                                <h1 class="font-semibold text-gray-900 text-sm line-clamp-2">
                                                                    {{ $variantTitle }}
                                                                </h1>
                                                            </div>

                                                            @if(!$item->color && !$item->size)
                                                                <div class="mb-2">
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded-md bg-blue-50 text-blue-700 text-xs">
                                                                        Standard Product
                                                                    </span>
                                                                </div>
                                                            @endif


                                                        </div>
                                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif



                    <!-- ============== Quantity ============== -->
                    <div class="my-6">
                        <label class="block text-sm font-medium text-gray-900 mb-3">Quantity:</label>
                        <div class="flex items-center gap-3">
                            <button wire:click="decrementQuantity" type="button"
                                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 12H4" />
                                </svg>
                            </button>
                            <input type="number" wire:model="quantity" min="1"
                                class="w-20 text-center border border-gray-300 rounded-lg py-2">
                            <button wire:click="incrementQuantity" type="button"
                                class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <button wire:click="addToCart" @disabled($product->stock_status !== 'in_stock') class="w-full py-3 px-6 rounded-lg font-semibold text-lg transition
                                                    {{ $product->stock_status === 'in_stock'
    ? 'bg-blue-600 text-white hover:bg-indigo-700'
    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                            {{ $product->stock_status === 'in_stock' ? 'Add to Cart' : 'Out of Stock' }}
                        </button>

                        <button wire:click="buyNow" @disabled($product->stock_status !== 'in_stock') class="w-full py-3 px-6 rounded-lg font-semibold text-lg transition
                                                    {{ $product->stock_status === 'in_stock'
    ? 'bg-blue-600 text-white hover:bg-indigo-700'
    : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                            Buy Now
                        </button>
                    </div>


                    <!-- Product meta -->
                    <div class="mt-8 border-t pt-6 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">SKU:</span>
                            <span class="font-medium">{{ $variant?->sku ?? $product->sku }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category:</span>
                            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                                class="font-medium text-blue-600 hover:text-indigo-700">{{ $product->category->name }}</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ================= Tabs: Description & Reviews ================= -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8" x-data="{ activeTab: 'description' }">
            <div class="border-b">
                <nav class="flex">
                    <button @click="activeTab = 'description'"
                        :class="{ 'border-blue-600 text-blue-600': activeTab === 'description' }"
                        class="px-6 py-4 border-b-2 font-medium transition">Description</button>
                    <button @click="activeTab = 'reviews'"
                        :class="{ 'border-blue-600 text-blue-600': activeTab === 'reviews' }"
                        class="px-6 py-4 border-b-2 font-medium transition">Reviews
                        ({{ $product->reviews_count }})</button>
                </nav>
            </div>
            <div class="p-8">
                <div x-show="activeTab === 'description'" x-cloak>
                    <div class="prose max-w-none">{!! $product->description !!}</div>
                </div>
                <div x-show="activeTab === 'reviews'" x-cloak>
                    @if($product->approvedReviews->count() > 0)
                        <div class="space-y-6">
                            @foreach($product->approvedReviews as $review)
                                <div class="border-b pb-6 last:border-b-0">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                                                {{ substr($review->customer->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-semibold">{{ $review->customer->name }}</h4>
                                                @if($review->is_verified_purchase)
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Verified
                                                        Purchase</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
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
                                                <span
                                                    class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if($review->title)
                                            <h5 class="font-medium mb-2">{{ $review->title }}</h5>@endif
                                            @if($review->comment)
                                            <p class="text-gray-700">{{ $review->comment }}</p>@endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-8">No reviews yet. Be the first to review this product!</p>
                    @endif
                </div>
            </div>
        </div>

        @if($relatedProducts->count() > 0)
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <livewire:product-card :product="$relatedProduct" :key="'related-' . $relatedProduct->id" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
</div>
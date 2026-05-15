<?php

use Livewire\Volt\Volt;
use App\Livewire\Orders;
use App\Livewire\CartPage;
use App\Livewire\Homepage;
use App\Livewire\CheckoutPage;
use App\Livewire\ProductDetails;
use App\Livewire\ProductListing;
use App\Livewire\Customer\Profile;
use App\Livewire\Customer\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Customer\OrderDetails;
use App\Http\Controllers\CheckoutController;

Route::get('/', Homepage::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('products', ProductListing::class)->name('products.index');
Route::get('product/{slug}', ProductDetails::class)->name('products.show');
Route::get('cart', CartPage::class)->name('cart.index');
Route::get('checkout', CheckoutPage::class)->name('checkout');

Route::middleware('auth:customer')->group(function () {
    Route::get('my-account', Dashboard::class)
        ->name('customer.dashboard');
    Route::post('logout', function () {
        auth()->guard('customer')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    });

    Route::get('/my-accounts/orders', Orders::class)->name('customer.orders');
    Route::get('/my-accounts/orders/{id}', OrderDetails::class)->name('customer.orders.show');
    Route::get('my-accounts/profile', Profile::class)->name('customer.profile');

      //checkout success/cancel routes
    Route::get('/checkout/success/{order}', [CheckoutController::class,'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class,'cancel'])->name('checkout.cancel');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

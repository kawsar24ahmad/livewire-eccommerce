<?php

use Livewire\Volt\Volt;
use App\Livewire\Orders;
use App\Livewire\CartPage;
use App\Livewire\Homepage;
use App\Livewire\ThankYou;
use App\Livewire\CheckoutPage;
use App\Livewire\ProductDetails;
use App\Livewire\ProductListing;
use App\Livewire\Customer\Profile;
use App\Livewire\Customer\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Customer\OrderDetails;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CheckoutController;

Route::get('/', Homepage::class)->name('home');


Route::get('dashboard', function () {
    // 1. Check if the user is logged in as a customer
    if (Auth::guard('customer')->check()) {
        return redirect()->route('customer.dashboard');
    }

    // 2. Otherwise, assume they are an Admin ('web' guard) and redirect to Filament
    // If your Filament panel is named 'admin', the route name is 'filament.admin.pages.dashboard'
    if (Route::has('filament.admin.pages.dashboard')) {
        return redirect()->route('filament.admin.pages.dashboard');
    }

    // Fallback directly to the /admin URL path if the route name isn't recognized
    return redirect('/admin');
})
    ->middleware(['auth:web,customer', 'verified']) // Crucial: Allow BOTH guards to pass through this check
    ->name('dashboard');

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
});

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
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});
Route::get('/order/thank-you', ThankYou::class)->name('order.success');

require __DIR__ . '/auth.php';

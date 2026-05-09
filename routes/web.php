<?php

use App\Livewire\Customer\Dashboard;
use App\Livewire\Customer\Profile;
use App\Livewire\Orders;
use App\Livewire\ProductListing;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('products', ProductListing::class)->name('products.index');

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
    Route::get('my-accounts/profile', Profile::class)->name('customer.profile');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';

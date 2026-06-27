<?php

use App\Models\User;
use App\Models\Customer;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

new #[Layout('components.layouts.customer')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Customer::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = Customer::create($validated);
        // event(new Registered(($user = Customer::create($validated))));

        Auth::guard('customer')->login($user);

        $this->redirect(route('customer.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-600">
                {{ config('app.name') }}
            </a>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-indigo-500">
                    Sign in
                </a>
            </p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <form wire:submit="register">

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input id="name" wire:model="name" type="text" name="name" value="{{ old('name') }}" required
                        autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input id="email" wire:model="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number (Optional)
                    </label>
                    <input id="phone" wire:model="phone" type="tel" name="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input id="password" wire:model="password" type="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password
                    </label>
                    <input id="password_confirmation" wire:model="password_confirmation" type="password"
                        name="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>



                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition font-semibold">
                    Create Account
                </button>
            </form>

            <!-- Benefits -->
            <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                <p class="text-sm font-medium text-indigo-900 mb-2">Why join us?</p>
                <ul class="text-sm text-indigo-700 space-y-1">
                    <li>✓ Track your orders easily</li>
                    <li>✓ Save multiple addresses</li>
                    <li>✓ Get exclusive member offers</li>
                    <li>✓ Faster checkout process</li>
                </ul>
            </div>
        </div>

        <!-- Back to Home -->
        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:text-indigo-500">
                ← Back to Home
            </a>
        </p>
    </div>
</div>
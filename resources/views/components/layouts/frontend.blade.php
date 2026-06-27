<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563eb">

    <title>{{ $title ?? config('app.name', 'E-Commerce Store') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Active nav indicator */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 0;
            height: 2px;
            background: #2563eb;
            transition: width .25s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        .mobile-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .mobile-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile search expand */
        .mobile-search {
            max-height: 0;
            overflow: hidden;
            transition: max-height .3s ease;
        }

        .mobile-search.open {
            max-height: 80px;
        }

        /* Hide scrollbar on mobile menu */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Glass bottom nav */
        .glass-nav {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-top: 1px solid rgba(229, 231, 235, 0.6);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            padding: 8px 4px;
            color: #6b7280;
            transition: color .2s ease, transform .15s ease;
            position: relative;
            -webkit-tap-highlight-color: transparent;
        }

        .bottom-nav-item:hover {
            color: #2563eb;
        }

        .bottom-nav-item:active {
            transform: scale(0.94);
        }

        .bottom-nav-item.active {
            color: #2563eb;
        }

        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 28px;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #6366f1);
            border-radius: 0 0 4px 4px;
        }

        .bottom-nav-item .label {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        /* Categories bottom sheet */
        .cat-sheet {
            transform: translateY(100%);
            transition: transform .35s cubic-bezier(.4, 0, .2, 1);
        }

        .cat-sheet.open {
            transform: translateY(0);
        }

        .cat-overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .cat-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        /* Safe area for iOS */
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>

    @filamentStyles
</head>

<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-sm shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <!-- Announcement bar (optional) -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs sm:text-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-2 text-center">
                Free shipping on orders over $50. Shop now and save big!
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Main Header Row -->
            <div class="flex items-center justify-between gap-2 sm:gap-4 py-3 sm:py-4">

                <!-- Mobile: Hamburger + Logo -->
                <div class="flex items-center gap-2 sm:gap-3 flex-1 sm:flex-none">
                    <!-- Mobile Menu Toggle -->
                    <button type="button" onclick="toggleMobileMenu()" aria-label="Open menu"
                        class="lg:hidden p-2 -ml-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm sm:text-base shadow-sm">
                            {{ strtoupper(substr(config('app.name', 'E'), 0, 1)) }}
                        </div>
                        <span class="text-lg sm:text-xl font-bold text-gray-900 truncate max-w-[140px] sm:max-w-none">
                            {{ config('app.name', 'E-Commerce') }}
                        </span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                {{-- <div class="hidden lg:block flex-1 max-w-2xl mx-6">
                    <livewire:search-bar />
                </div> --}}

                <!-- Right Side Actions -->
                <div class="flex items-center gap-1 sm:gap-2">
                    <!-- Mobile Search Toggle -->
                    {{-- <button type="button" onclick="toggleMobileSearch()" aria-label="Search"
                        class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </button> --}}

                    @auth('customer')
                        <a href="{{ route('customer.dashboard') }}"
                            class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition"
                            title="My Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:inline text-sm font-medium">Account</span>
                        </a>

                        <a href="{{ route('customer.dashboard') }}"
                            class="sm:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition"
                            title="My Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit"
                                class="p-2 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition"
                                title="Log Out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>

                    @elseif(auth('web')->check())
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition"
                            title="My Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:inline text-sm font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="sm:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition"
                            title="My Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit"
                                class="p-2 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition"
                                title="Log Out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>

                    @else
                        <a href="{{ route('login') }}"
                            class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="hidden md:inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition">
                            Sign Up
                        </a>
                        <a href="{{ route('login') }}"
                            class="sm:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition"
                            title="Login">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    @endauth

                    <!-- Cart -->
                    <livewire:cart-icon />
                </div>
            </div>

            <!-- Mobile Search Expand -->
            {{-- <div id="mobileSearch" class="mobile-search lg:hidden">
                <div class="pb-3">
                    <livewire:search-bar />
                </div>
            </div> --}}

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block border-t border-gray-100 py-3">
                <ul class="flex items-center gap-8">
                    <li>
                        <a href="{{ route('home') }}"
                            class="nav-link text-sm font-medium text-gray-700 hover:text-blue-600 transition {{ request()->routeIs('home') ? 'active text-blue-600' : '' }}">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}"
                            class="nav-link text-sm font-medium text-gray-700 hover:text-blue-600 transition {{ request()->routeIs('products.*') ? 'active text-blue-600' : '' }}">
                            Shop
                        </a>
                    </li>
                    @foreach(\App\Models\Category::active()->sorted()->limit(5)->get() as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                class="nav-link text-sm font-medium text-gray-700 hover:text-blue-600 transition">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileOverlay" class="mobile-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"
        onclick="toggleMobileMenu()"></div>

    <!-- Mobile Slide-out Menu -->
    <aside id="mobileMenu"
        class="mobile-menu fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] bg-white z-50 lg:hidden shadow-2xl overflow-y-auto no-scrollbar">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(config('app.name', 'E'), 0, 1)) }}
                </div>
                <span class="font-bold text-gray-900">{{ config('app.name') }}</span>
            </div>
            <button type="button" onclick="toggleMobileMenu()" aria-label="Close menu"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="p-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Shop All
                    </a>
                </li>
                <li class="pt-3 pb-1">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>
                </li>
                @foreach(\App\Models\Category::active()->sorted()->limit(5)->get() as $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach

                @auth('customer')
                    <li class="pt-3 pb-1">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>
                    </li>
                    <li>
                        <a href="{{ route('customer.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.orders') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            My Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.profile') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Profile
                        </a>
                    </li>
                    <li class="pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </li>
                @else
                    <li class="pt-3">
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition">
                            Login
                        </a>
                    </li>
                    <li class="pt-2">
                        <a href="{{ route('register') }}"
                            class="flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow-sm transition">
                            Create Account
                        </a>
                    </li>
                @endauth
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 pb-24 lg:pb-0">
        {{ $slot }}
    </main>

    @livewire('notifications')

    <!-- Glass Bottom Navigation (Mobile Only) -->
    <nav
        class="glass-nav fixed bottom-0 left-0 right-0 z-30 lg:hidden safe-bottom shadow-[0_-2px_10px_rgba(0,0,0,0.04)]">
        <ul class="grid grid-cols-4">
            <li>
                <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="label">Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}"
                    class="bottom-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="label">Shop</span>
                </a>
            </li>
            <li>
                <button type="button" onclick="toggleCatSheet()" class="bottom-nav-item w-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="label">Categories</span>
                </button>
            </li>
            <li>
                <a href="{{ auth('customer')->check() ? route('customer.dashboard') : route('login') }}"
                    class="bottom-nav-item {{ request()->routeIs('customer.dashboard') || request()->routeIs('customer.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="label">Account</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Categories Bottom Sheet Overlay -->
    <div id="catOverlay" class="cat-overlay fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="toggleCatSheet()"></div>

    <!-- Categories Bottom Sheet -->
    <div id="catSheet" class="cat-sheet fixed bottom-0 left-0 right-0 z-50 lg:hidden safe-bottom">
        <div class="glass-nav rounded-t-3xl shadow-2xl">
            <div class="flex items-center justify-center pt-3 pb-1">
                <div class="w-10 h-1.5 bg-gray-300 rounded-full"></div>
            </div>
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100/60">
                <h3 class="text-lg font-bold text-gray-900">Categories</h3>
                <button type="button" onclick="toggleCatSheet()" aria-label="Close"
                    class="p-2 -mr-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <ul class="max-h-[60vh] overflow-y-auto no-scrollbar px-2 py-2">
                <li>
                    <a href="{{ route('products.index') }}" onclick="toggleCatSheet()"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition">
                        <span
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </span>
                        <span>All Products</span>
                    </a>
                </li>
                @foreach(\App\Models\Category::active()->sorted()->limit(5)->get() as $index => $category)
                    <li>
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" onclick="toggleCatSheet()"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                            <span
                                class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                                {{ strtoupper(substr($category->name, 0, 1)) }}
                            </span>
                            <span class="flex-1">{{ $category->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(config('app.name', 'E'), 0, 1)) }}
                        </div>
                        <h3 class="text-lg font-bold text-white">{{ config('app.name') }}</h3>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Your one-stop shop for quality products at
                        unbeatable prices.</p>
                    <div class="flex items-center gap-3 mt-4">
                        <a href="#" aria-label="Facebook"
                            class="w-9 h-9 rounded-full bg-gray-800 hover:bg-blue-600 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter"
                            class="w-9 h-9 rounded-full bg-gray-800 hover:bg-sky-500 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                            </svg>
                        </a>
                        <a href="#" aria-label="Instagram"
                            class="w-9 h-9 rounded-full bg-gray-800 hover:bg-pink-600 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">Shop</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}"
                                class="text-gray-400 hover:text-white transition">All Products</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Featured</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">New Arrivals</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Sale</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Returns</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-white">My Account</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('customer.dashboard') }}"
                                class="text-gray-400 hover:text-white transition">Dashboard</a></li>
                        <li><a href="{{ route('customer.orders') }}"
                                class="text-gray-400 hover:text-white transition">Orders</a></li>
                        <li><a href="{{ route('customer.profile') }}"
                                class="text-gray-400 hover:text-white transition">Profile</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Wishlist</a></li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-gray-800 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                    <a href="#" class="hover:text-white transition">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    @filamentScripts

    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('open');
            document.getElementById('mobileOverlay').classList.toggle('open');
            document.body.classList.toggle('overflow-hidden');
        }

        // Mobile search toggle
        function toggleMobileSearch() {
            document.getElementById('mobileSearch').classList.toggle('open');
            // close other panels
            const menu = document.getElementById('mobileMenu');
            if (menu.classList.contains('open')) toggleMobileMenu();
            // focus input when opened
            setTimeout(() => {
                const input = document.querySelector('#mobileSearch input');
                if (input && document.getElementById('mobileSearch').classList.contains('open')) input.focus();
            }, 300);
        }

        // Categories bottom sheet toggle
        function toggleCatSheet() {
            const sheet = document.getElementById('catSheet');
            const overlay = document.getElementById('catOverlay');
            const isOpen = sheet.classList.contains('open');
            sheet.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.classList.toggle('overflow-hidden', !isOpen);
        }

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const menu = document.getElementById('mobileMenu');
                const sheet = document.getElementById('catSheet');
                if (sheet && sheet.classList.contains('open')) {
                    toggleCatSheet();
                } else if (menu && menu.classList.contains('open')) {
                    toggleMobileMenu();
                }
            }
        });

        // Close menu on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                const menu = document.getElementById('mobileMenu');
                const overlay = document.getElementById('mobileOverlay');
                if (menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    overlay.classList.remove('open');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });
    </script>
</body>

</html>
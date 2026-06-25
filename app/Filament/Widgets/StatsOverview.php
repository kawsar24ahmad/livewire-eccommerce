<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return ! auth()->user()?->hasRole('staff');
    }

    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $todayRevenue = Order::where('payment_status', 'paid')
        ->whereYear('created_at',today())->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereYear('created_at', now()->month)->count();
        $lowStockProduct = Product::lowStock()->count();
        return [
            Stat::make('Total Revenue', $totalRevenue)
                ->description("Today $". number_format($todayRevenue))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Orders', $totalOrders)
                ->description("Pending : " . $pendingOrders)
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning')
                ->url(route('filament.admin.resources.orders.index')),
            Stat::make('Total Customers', $totalCustomers)
                ->description($newCustomers ." new this month")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->url(route('filament.admin.resources.customers.index')),
            Stat::make('Low Stock Alert', $lowStockProduct)
                ->description("Products running low")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('danger')
                ->url(route('filament.admin.resources.products.index')),
        ];
    }
}

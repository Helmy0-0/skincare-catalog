<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use \App\Models\Order;
use \App\Models\User;
use \Carbon\Carbon;

class AStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $now = Carbon::now();
        $since = $now->subDays(30);

        $revenue = Order::where('order_date', '>=', $since)
            ->sum('total_amount');

        $newCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', $since)
            ->count();

        $newOrders = Order::where('order_date', '>=', $since)->count();

       $formattedRevenue = 'Rp ' . number_format((float) $revenue, 0, ',', '.');

        return [
            Stat::make('Revenue', $formattedRevenue),
            Stat::make('New Customers', (string) $newCustomers),
            Stat::make('New Orders', (string) $newOrders),
        ];
    }
}

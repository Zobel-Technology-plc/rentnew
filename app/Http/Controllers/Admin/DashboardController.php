<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Rental;
use App\Models\Transaction;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // Get current month's data
        $currentMonthProperties = Property::whereMonth('created_at', $now->month)->count();
        $currentMonthRentals = Rental::whereMonth('created_at', $now->month)->count();
        $currentMonthRevenue = Transaction::whereMonth('created_at', $now->month)
            ->where('status', 'completed')
            ->sum('amount');

        // Get last month's data for comparison
        $lastMonthProperties = Property::whereMonth('created_at', $lastMonth->month)->count();
        $lastMonthRentals = Rental::whereMonth('created_at', $lastMonth->month)->count();
        $lastMonthRevenue = Transaction::whereMonth('created_at', $lastMonth->month)
            ->where('status', 'completed')
            ->sum('amount');

        return view('admin.dashboard', [
            'totalProperties' => Property::count(),
            'propertyGrowth' => $this->calculateGrowth($currentMonthProperties, $lastMonthProperties),
            
            'activeRentals' => Rental::where('status', 'active')->count(),
            'rentalGrowth' => $this->calculateGrowth($currentMonthRentals, $lastMonthRentals),
            
            'monthlyRevenue' => $currentMonthRevenue,
            'revenueGrowth' => $this->calculateGrowth($currentMonthRevenue, $lastMonthRevenue),
            
            'pendingRequests' => Rental::where('status', 'pending')->count(),
            'urgentRequests' => Rental::where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(3))
                ->count(),

            'recentRentals' => Rental::with(['property', 'tenant'])
                ->latest()
                ->take(5)
                ->get(),

            'recentTransactions' => Transaction::with(['rental.property', 'user'])
                ->where('status', 'completed')
                ->latest()
                ->take(5)
                ->get(),

            'rentalStatusDistribution' => [
                'active' => Rental::where('status', 'active')->count(),
                'pending' => Rental::where('status', 'pending')->count(),
                'completed' => Rental::where('status', 'completed')->count()
            ],

            'monthlyRevenues' => $this->getMonthlyRevenues(),
        ]);
    }

    private function calculateGrowth($current, $previous): float
    {
        if ($previous == 0) return 100;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function getMonthlyRevenues(): array
    {
        $months = collect(range(5, 0))->map(function($month) {
            $date = now()->subMonths($month);
            return [
                'month' => $date->format('M'),
                'revenue' => Transaction::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->where('status', 'completed')
                    ->sum('amount')
            ];
        });

        return [
            'labels' => $months->pluck('month'),
            'data' => $months->pluck('revenue')
        ];
    }

    private function getUserGrowthData(): array
    {
        $months = collect(range(5, 0))->map(function($month) {
            $date = now()->subMonths($month);
            return [
                'month' => $date->format('M'),
                'count' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        });

        return [
            'labels' => $months->pluck('month'),
            'data' => $months->pluck('count'),
        ];
    }

    private function getRecentActivity(): Collection
    {
        return User::select('name', 'email', 'created_at', 'status')
            ->latest()
            ->take(10)
            ->get();
    }
} 
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'newUsersToday' => User::whereDate('created_at', today())->count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'blockedUsers' => User::where('status', 'blocked')->count(),
            'userGrowth' => $this->getUserGrowthData(),
            'recentActivity' => $this->getRecentActivity(),
        ];

        return view('superadmin.dashboard', $data);
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

    private function getRecentActivity()
    {
        return User::select('name', 'email', 'created_at', 'status')
            ->latest()
            ->take(10)
            ->get();
    }
} 
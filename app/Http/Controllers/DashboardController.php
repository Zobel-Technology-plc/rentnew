<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'newUsersToday' => User::whereDate('created_at', today())->count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'blockedUsers' => User::where('status', 'blocked')->count(),
            'userGrowth' => $this->getUserGrowthData(),
        ];

        return view('dashboard', $data);
    }

    private function getUserGrowthData()
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
} 
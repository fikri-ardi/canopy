<?php

namespace App\Livewire;

use App\Models\Feedback;
use App\Models\User;
use Livewire\Component;

class AdminAnalytics extends Component
{
    public function render()
    {
        $activeWindow = now()->subMinutes(5);
        $dayStart = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        return view('livewire.admin-analytics', [
            'currentlyActiveUsers' => User::query()
                ->with('role')
                ->where('last_seen_at', '>=', $activeWindow)
                ->latest('last_seen_at')
                ->limit(8)
                ->get(),
            'activeNow' => User::where('last_seen_at', '>=', $activeWindow)->count(),
            'dailyActiveUsers' => User::where('last_seen_at', '>=', $dayStart)->count(),
            'monthlyActiveUsers' => User::where('last_seen_at', '>=', $monthStart)->count(),
            'totalUsers' => User::count(),
            'recentUsers' => User::query()
                ->with('role')
                ->latest('created_at')
                ->limit(6)
                ->get(),
            'recentFeedback' => Feedback::query()
                ->with('user')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}

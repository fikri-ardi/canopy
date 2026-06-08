<?php

namespace App\Livewire;

use App\Models\Feedback;
use App\Models\User;
use App\Models\UserActivityDay;
use Livewire\Component;

class AdminAnalytics extends Component
{
    public string $recentUsersSortField = 'created_at';

    public string $recentUsersSortDirection = 'desc';

    public int $recentUsersLimit = 6;

    private int $recentUsersStep = 10;

    public function sortRecentUsers(string $field): void
    {
        if (! array_key_exists($field, $this->recentUsersSortColumns())) {
            return;
        }

        if ($this->recentUsersSortField === $field) {
            $this->recentUsersSortDirection = $this->recentUsersSortDirection === 'asc' ? 'desc' : 'asc';

            return;
        }

        $this->recentUsersSortField = $field;
        $this->recentUsersSortDirection = $field === 'created_at' || $field === 'last_seen_at' ? 'desc' : 'asc';
    }

    public function showMoreRecentUsers(): void
    {
        $this->recentUsersLimit += $this->recentUsersStep;
    }

    public function showLessRecentUsers(): void
    {
        $this->recentUsersLimit = 6;
    }

    public function render()
    {
        $dayStart = now()->startOfDay();
        $monthStart = now()->startOfMonth();
        $recentUsersTotal = User::count();

        return view('livewire.admin-analytics', [
            'currentlyActiveUsers' => User::query()
                ->with('role')
                ->where('online_until', '>=', now())
                ->latest('last_seen_at')
                ->limit(8)
                ->get(),
            'activeNow' => User::where('online_until', '>=', now())->count(),
            'dailyActiveUsers' => $this->activeUsersCount($dayStart->toDateString(), now()->toDateString()),
            'monthlyActiveUsers' => $this->activeUsersCount($monthStart->toDateString(), now()->endOfMonth()->toDateString()),
            'totalUsers' => User::count(),
            'recentUsers' => $this->recentUsers(),
            'recentUsersTotal' => $recentUsersTotal,
            'recentUsersShowing' => min($this->recentUsersLimit, $recentUsersTotal),
            'recentUsersNextCount' => min($this->recentUsersStep, max(0, $recentUsersTotal - $this->recentUsersLimit)),
            'recentFeedback' => Feedback::query()
                ->with('user')
                ->latest()
                ->limit(8)
                ->get(),
            'recentUsersSortField' => $this->recentUsersSortField,
            'recentUsersSortDirection' => $this->recentUsersSortDirection,
            'refreshedAt' => now(),
        ]);
    }

    private function activeUsersCount(string $from, string $to): int
    {
        return UserActivityDay::query()
            ->whereDate('active_on', '>=', $from)
            ->whereDate('active_on', '<=', $to)
            ->distinct()
            ->pluck('user_id')
            ->count();
    }

    private function recentUsers()
    {
        $sortColumns = $this->recentUsersSortColumns();
        $sortField = $sortColumns[$this->recentUsersSortField] ?? $sortColumns['created_at'];
        $sortDirection = $this->recentUsersSortDirection === 'asc' ? 'asc' : 'desc';
        $query = User::query()
            ->with('role')
            ->select('users.*');

        if ($this->recentUsersSortField === 'role') {
            $query->leftJoin('roles', 'roles.id', '=', 'users.role_id');
        }

        if ($this->recentUsersSortField === 'last_seen_at') {
            $query->orderByRaw('users.last_seen_at is null');
        }

        return $query
            ->orderBy($sortField, $sortDirection)
            ->orderBy('users.id', 'desc')
            ->limit($this->recentUsersLimit)
            ->get();
    }

    private function recentUsersSortColumns(): array
    {
        return [
            'name' => 'users.name',
            'email' => 'users.email',
            'role' => 'roles.label',
            'created_at' => 'users.created_at',
            'last_seen_at' => 'users.last_seen_at',
        ];
    }
}

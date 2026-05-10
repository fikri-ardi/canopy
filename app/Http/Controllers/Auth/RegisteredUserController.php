<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create($validated);

        $this->claimExistingLocalData($user);
        $this->createDefaultTrackingSettings($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    private function claimExistingLocalData(User $user): void
    {
        if (User::query()->count() !== 1) {
            return;
        }

        if (Schema::hasColumn('budgets', 'user_id')) {
            Budget::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        }

        if (Schema::hasTable('labels') && Schema::hasColumn('labels', 'user_id')) {
            Label::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        }

        if (Schema::hasColumn('platforms', 'user_id')) {
            Platform::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        }

        if (Schema::hasColumn('statuses', 'user_id')) {
            Status::query()->whereNull('user_id')->update(['user_id' => $user->id]);
        }
    }

    private function createDefaultTrackingSettings(User $user): void
    {
        if (Schema::hasColumn('platforms', 'user_id') && ! Platform::where('user_id', $user->id)->exists()) {
            collect(['Cash', 'Main Bank', 'E-Wallet'])->each(fn ($platform) => Platform::create([
                'user_id' => $user->id,
                'name' => $platform,
            ]));
        }

        if (Schema::hasColumn('statuses', 'user_id') && ! Status::where('user_id', $user->id)->exists()) {
            collect(['Unallocated', 'Allocated', 'Withdrawn', 'Done'])->each(fn ($status) => Status::create([
                'user_id' => $user->id,
                'body' => $status,
            ]));
        }
    }
}

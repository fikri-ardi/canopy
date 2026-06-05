<?php

namespace App\Support;

use App\Models\Budget;
use App\Models\Label;
use App\Models\Platform;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

trait InitializesUserAccount
{
    private function initializeUserAccount(User $user): void
    {
        $this->claimExistingLocalData($user);
        $this->createDefaultTrackingSettings($user);
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
        if (Schema::hasTable('labels') && Schema::hasColumn('labels', 'user_id') && ! Label::where('user_id', $user->id)->exists()) {
            collect(['elektronik', 'investasi', 'jajan', 'kebutuhan', 'tagihan', 'transport'])->each(fn ($label) => Label::firstOrCreate([
                'user_id' => $user->id,
                'name' => $label,
            ]));
        }

        if (Schema::hasColumn('platforms', 'user_id') && ! Platform::where('user_id', $user->id)->exists()) {
            collect(['Cash', 'GoPay', 'Shopeepay', 'OVO', 'Dana', 'BNI', 'BRI', 'BCA'])->each(fn ($platform) => Platform::firstOrCreate([
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

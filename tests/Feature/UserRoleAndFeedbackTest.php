<?php

use App\Livewire\AdminAnalytics;
use App\Livewire\Settings;
use App\Models\Feedback;
use App\Models\Role;
use App\Models\User;
use App\Models\UserActivityDay;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('assigns the default user role to new users', function () {
    $user = User::factory()->create();

    expect($user->fresh()->role?->name)->toBe('user');
});

it('protects admin analytics from non admin users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.analytics'))
        ->assertForbidden();
});

it('allows admin users to access admin analytics', function () {
    $admin = User::factory()->create([
        'role_id' => Role::where('name', 'admin')->value('id'),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.analytics'))
        ->assertOk();
});

it('stores feedback from settings', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('feedbackMood', 'idea')
        ->set('feedbackMessage', 'The dashboard date filter feels nice, but I want presets too.')
        ->call('sendFeedback')
        ->assertDispatched('alokasi-flash');

    expect(Feedback::where('user_id', $user->id)->where('mood', 'idea')->exists())->toBeTrue();
});

it('marks users offline when they log out', function () {
    $user = User::factory()->create([
        'last_seen_at' => now(),
        'online_until' => now()->addMinutes(5),
    ]);

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    expect($user->fresh()->last_seen_at)->not->toBeNull()
        ->and($user->fresh()->online_until)->toBeNull();
});

it('keeps daily activity after logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings'))
        ->assertOk();

    expect(UserActivityDay::where('user_id', $user->id)->whereDate('active_on', today())->exists())->toBeTrue()
        ->and($user->fresh()->last_seen_at)->not->toBeNull();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    expect($user->fresh()->last_seen_at)->not->toBeNull()
        ->and($user->fresh()->online_until)->toBeNull()
        ->and(UserActivityDay::where('user_id', $user->id)->whereDate('active_on', today())->exists())->toBeTrue();
});

it('keeps monthly active users at least as high as daily active users', function () {
    UserActivityDay::query()->delete();

    $admin = User::factory()->create([
        'role_id' => Role::where('name', 'admin')->value('id'),
    ]);
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();
    $previousMonthUser = User::factory()->create();

    UserActivityDay::create([
        'user_id' => $firstUser->id,
        'active_on' => today(),
    ]);
    UserActivityDay::create([
        'user_id' => $secondUser->id,
        'active_on' => today(),
    ]);
    UserActivityDay::create([
        'user_id' => $previousMonthUser->id,
        'active_on' => today()->subMonthNoOverflow(),
    ]);

    Livewire::actingAs($admin)
        ->test(AdminAnalytics::class)
        ->assertViewHas('dailyActiveUsers', 2)
        ->assertViewHas('monthlyActiveUsers', 2);
});

it('sorts recent users from the admin analytics table headers', function () {
    $admin = User::factory()->create([
        'role_id' => Role::where('name', 'admin')->value('id'),
    ]);

    Livewire::actingAs($admin)
        ->test(AdminAnalytics::class)
        ->assertSet('recentUsersSortField', 'created_at')
        ->assertSet('recentUsersSortDirection', 'desc')
        ->call('sortRecentUsers', 'email')
        ->assertSet('recentUsersSortField', 'email')
        ->assertSet('recentUsersSortDirection', 'asc')
        ->call('sortRecentUsers', 'email')
        ->assertSet('recentUsersSortDirection', 'desc')
        ->call('sortRecentUsers', 'not_a_column')
        ->assertSet('recentUsersSortField', 'email');
});

it('loads more recent users in steps and can collapse the list', function () {
    $admin = User::factory()->create([
        'role_id' => Role::where('name', 'admin')->value('id'),
    ]);

    User::factory()->count(30)->create();

    Livewire::actingAs($admin)
        ->test(AdminAnalytics::class)
        ->assertSet('recentUsersLimit', 6)
        ->assertViewHas('recentUsersShowing', 6)
        ->assertViewHas('recentUsersNextCount', 10)
        ->call('showMoreRecentUsers')
        ->assertSet('recentUsersLimit', 16)
        ->assertViewHas('recentUsersShowing', 16)
        ->assertViewHas('recentUsersNextCount', 10)
        ->call('showMoreRecentUsers')
        ->assertSet('recentUsersLimit', 26)
        ->assertViewHas('recentUsersShowing', 26)
        ->call('showLessRecentUsers')
        ->assertSet('recentUsersLimit', 6)
        ->assertViewHas('recentUsersShowing', 6);
});

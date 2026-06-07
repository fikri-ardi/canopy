<?php

use App\Livewire\Settings;
use App\Models\Feedback;
use App\Models\Role;
use App\Models\User;
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

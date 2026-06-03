<?php

use App\Livewire\Settings;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

it('updates profile asynchronously and dispatches a flash notification', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->set('name', 'New Name')
        ->set('email', $user->email)
        ->call('updateProfile')
        ->assertDispatched('canopy-flash');

    expect($user->fresh()->name)->toBe('New Name');
});

it('sets a password for a passwordless user asynchronously', function () {
    $user = User::factory()->create([
        'password' => null,
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->assertSet('hasPassword', false)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertSet('hasPassword', true)
        ->assertDispatched('canopy-flash');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

it('changes an existing password asynchronously', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(Settings::class)
        ->assertSet('hasPassword', true)
        ->set('currentPassword', 'old-password')
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('updatePassword')
        ->assertDispatched('canopy-flash');

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

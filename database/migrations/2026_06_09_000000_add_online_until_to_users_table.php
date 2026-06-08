<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('online_until')->nullable()->after('last_seen_at')->index();
        });

        foreach (\App\Models\User::whereNotNull('last_seen_at')->get(['id', 'last_seen_at']) as $user) {
            $user->forceFill([
                'online_until' => $user->last_seen_at?->copy()->addMinutes(5),
            ])->saveQuietly();
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('online_until');
        });
    }
};

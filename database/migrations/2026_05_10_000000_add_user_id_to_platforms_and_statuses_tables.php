<?php

use App\Models\Platform;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platforms', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('statuses', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        $firstUserId = User::query()->oldest('id')->value('id');

        if ($firstUserId) {
            Platform::query()->whereNull('user_id')->update(['user_id' => $firstUserId]);
            Status::query()->whereNull('user_id')->update(['user_id' => $firstUserId]);
        }
    }

    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

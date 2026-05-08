<?php

use App\Models\Budget;
use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        if (Schema::hasTable('labels')) {
            Schema::table('labels', function (Blueprint $table) {
                $table->foreignIdFor(User::class)->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        $firstUserId = User::query()->oldest('id')->value('id');

        if ($firstUserId) {
            Budget::query()->whereNull('user_id')->update(['user_id' => $firstUserId]);

            if (Schema::hasTable('labels')) {
                Label::query()->whereNull('user_id')->update(['user_id' => $firstUserId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('labels') && Schema::hasColumn('labels', 'user_id')) {
            Schema::table('labels', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        $now = now();
        DB::table('roles')->insert([
            ['name' => 'admin', 'label' => 'Admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'user', 'label' => 'User', 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id')->constrained('roles')->nullOnDelete();
            $table->timestamp('last_seen_at')->nullable()->after('email_verified_at')->index();
        });

        DB::table('users')->update([
            'role_id' => DB::table('roles')->where('name', 'user')->value('id'),
        ]);

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('mood', 24)->default('idea');
            $table->text('message');
            $table->string('page')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'last_seen_at']);
        });

        Schema::dropIfExists('roles');
    }
};

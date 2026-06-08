<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activity_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('active_on');
            $table->timestamps();

            $table->unique(['user_id', 'active_on']);
            $table->index('active_on');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activity_days');
    }
};

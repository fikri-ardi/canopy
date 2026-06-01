<?php

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
        Schema::create('investment_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('investment_key');
            $table->string('investment_name');
            $table->string('type', 24);
            $table->unsignedBigInteger('amount');
            $table->date('occurred_on');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'investment_key']);
            $table->index(['user_id', 'type']);
            $table->index('occurred_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_movements');
    }
};

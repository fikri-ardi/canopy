<?php

use App\Models\Budget;
use App\Models\FinancialGoal;
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
        Schema::create('financial_goal_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FinancialGoal::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Budget::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('type');
            $table->integer('amount');
            $table->text('note')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_goal_movements');
    }
};
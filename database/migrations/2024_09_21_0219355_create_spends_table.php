<?php

use App\Models\Budget;
use App\Models\Platform;
use App\Models\Status;
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
        Schema::create('spends', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Budget::class)->constrained();
            $table->foreignIdFor(Platform::class)->constrained();
            $table->foreignIdFor(Status::class)->constrained();
            $table->string('name');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spends');
    }
};

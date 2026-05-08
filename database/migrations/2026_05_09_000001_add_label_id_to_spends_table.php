<?php

use App\Models\Label;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spends', function (Blueprint $table) {
            $table->foreignIdFor(Label::class)->nullable()->after('status_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('spends', function (Blueprint $table) {
            $table->dropConstrainedForeignId('label_id');
        });
    }
};

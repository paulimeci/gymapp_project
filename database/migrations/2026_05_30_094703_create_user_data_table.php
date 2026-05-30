<?php

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
        Schema::create('human_user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('data');
            $table->float('gjatesia');
            $table->float('pesha');

            // Krijohen si string, por me kushtin CHECK që funksionon si në MySQL ashtu edhe në SQLite
            $table->string('njesia_peshes')->default('kg')->checkIn(['kg', 'lbs']);
            $table->string('njesia_gjatesise')->default('cm')->checkIn(['cm', 'in']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_user_data');
    }
};

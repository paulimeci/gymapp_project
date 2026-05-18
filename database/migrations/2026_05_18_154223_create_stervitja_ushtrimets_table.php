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
        Schema::create('act_stervitja_ushtrimet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_stervitjes')->constrained('act_stervitja')->cascadeOnDelete();
            $table->foreignId('id_ushtrimit')->constrained('excs_ushtrimet')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_stervitja_ushtrimet');
    }
};

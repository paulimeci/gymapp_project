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
        Schema::create('excs_kategoria_ushtrimet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategoria_id')->constrained('excs_kategorite')->cascadeOnDelete();
            $table->foreignId('ushtrimet_id')->constrained('excs_ushtrimet')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // parandalon duplikate
            $table->unique(['user_id', 'kategoria_id', 'ushtrimet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excs_kategoria_ushtrimet');
    }
};

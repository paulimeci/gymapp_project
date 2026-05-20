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
        Schema::create('act_stervitja_ushtrimet_detaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ushtrimit_exct')->constrained('act_stervitja_ushtrimet')->cascadeOnDelete();
            $table->integer('reps');
            $table->decimal('pesha');
            $table->integer('kohezgjatja_sekonda')->nullable(); // Më mirë në sekonda, e kthen në minuta në Frontend/App
            $table->decimal('distanca', 8, 2)->nullable(); // Për vrap/biçikletë (p.sh. 5.4 km)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_stervitja_ushtrimet_detaje');
    }
};

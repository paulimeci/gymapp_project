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
        Schema::create('human_pjeset_e_trupit', function (Blueprint $table) {
            $table->id();
            $table->string('emri');
            $table->timestamps();
        });
        Artisan::call('db:seed', [
            '--class' => 'PjesetEtrupitSeeder'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('human_pjeset_e_trupit');
    }
};

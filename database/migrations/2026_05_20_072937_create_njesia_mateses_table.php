<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan; // 1. Shto këtë rresht lart

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('excs_njesia_matese', function (Blueprint $table) {
            $table->id();
            $table->string('emri');
            $table->string('shkurtimi');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'NjesiaMateseEVeprimtarise'
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excs_njesia_matese');
    }
};

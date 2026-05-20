<?php

namespace Database\Seeders;

use App\Models\Human\PjesetETrupit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PjesetEtrupitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PjesetETrupit::create(['emri' => 'shpine']);
        PjesetETrupit::create(['emri' => 'shpatull']);
        PjesetETrupit::create(['emri' => 'gjoks']);
        PjesetETrupit::create(['emri' => 'bark']);
        PjesetETrupit::create(['emri' => 'biceps']);
        PjesetETrupit::create(['emri' => 'triceps']);
        PjesetETrupit::create(['emri' => 'quadriceps']);
        PjesetETrupit::create(['emri' => 'biceps_femoris']); // Ose 'prapavija-e-kofshes' nëse preferon shqip pa karaktere speciale
        PjesetETrupit::create(['emri' => 'pulpe']);
    }
}

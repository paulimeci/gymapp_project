<?php

namespace Database\Seeders;

use App\Models\Structure\NjesiaMatese;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NjesiaMateseEVeprimtarise extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NjesiaMatese::create(['emri' => 'Përsëritje dhe Peshë', 'shkurtimi' => 'reps/kg']);
        NjesiaMatese::create(['emri' => 'Kohëzgjatje', 'shkurtimi' => 'min']);
    }
}

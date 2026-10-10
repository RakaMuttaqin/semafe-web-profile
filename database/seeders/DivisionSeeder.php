<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = ['SEKBEN', 'INFOKOM', 'P3A', 'KPP', 'SOSIAL'];

        foreach ($divisions as $division) {
            Division::factory()->create([
                'name' => $division,
                'slug' => str_replace(' ', '-', strtolower($division)),
            ]);
        }
    }
}

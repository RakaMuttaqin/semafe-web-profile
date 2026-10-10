<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Admin', 'Operator', 'Media'];

        foreach ($names as $name) {
            Role::factory()->create([
                'name' => $name,
                'slug' => str_replace(' ', '-', strtolower($name)),
            ]);
        }
    }
}

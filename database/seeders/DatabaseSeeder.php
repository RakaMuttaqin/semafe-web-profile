<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DivisionSeeder::class,
            // MemberSeeder::class,
        ]);

        $roles = Role::all();

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role_id' => $roles->firstWhere('slug', 'admin')?->id,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Operator',
                'email' => 'operator@example.com',
                'role_id' => $roles->firstWhere('slug', 'operator')?->id,
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Media',
                'email' => 'media@example.com',
                'role_id' => $roles->firstWhere('slug', 'media')?->id,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
    }
}

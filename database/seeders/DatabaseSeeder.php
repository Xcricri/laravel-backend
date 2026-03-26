<?php

namespace Database\Seeders;

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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        User::factory(5)->create([
            'role' => 'admin',
            'password' => Hash::make('passwordUser'),
        ]);
    }
}

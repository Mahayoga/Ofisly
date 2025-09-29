<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => password_hash('admin1234', PASSWORD_BCRYPT, ['cost' => 12]),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => password_hash('user1234', PASSWORD_BCRYPT, ['cost' => 12]),
            'role' => 'user'
        ]);
    }
}

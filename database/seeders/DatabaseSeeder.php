<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verity_token' => Str::random(64),
            'password' => bcrypt('1234AAAA'),
            'birth' => fake()->date(),
            'sex' => fake()->boolean(),
        ])->update(["state" => 1, "is_admin" => true]);

        User::factory()->count(30)->create(["state" => 1]);
    }
}

<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\UserProfiles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DatabaseSeeder::run()
        $this->call([LocationSeeder::class, PropertyTypeSeeder::class, UserSeeder::class, PropertySeeder::class]);

        User::factory()->create([
            'password' => 'testpassword',
            'email' => 'test@example.com',
        ]);
    }
}

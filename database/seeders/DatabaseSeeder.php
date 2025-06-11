<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('id', 1)->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
            ]);
        }

        $this->call(ProductSeeder::class);
    }
}

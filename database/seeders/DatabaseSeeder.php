<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => env('SAMPLE_USER_NAME'),
            'email' => env('SAMPLE_USER_EMAIL'),
            'password' => Hash::make(env('SAMPLE_USER_PASS'))
        ]);
    }
}

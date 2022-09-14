<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => "Admin",
            'email' => 'admin@email.com',
            'role' => 1
        ]);

        User::factory()->create([
            'name' => "Recruiter",
            'email' => 'recruiter@email.com',
            'role' => 2
        ]);

        User::factory()->create([
            'name' => "Interviewer",
            'email' => 'interviewer@email.com',
            'role' => 3
        ]);
    }
}

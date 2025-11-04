<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $userA = User::factory()->create([
            'name' => 'User A',
            'email' => 'usera@example.test',
            'password' => bcrypt('password'),
        ]);

        $userB = User::factory()->create([
            'name' => 'User B',
            'email' => 'userb@example.test',
            'password' => bcrypt('password'),
        ]);

        Course::factory()->count(3)->for($userA, 'user')->create();
        Course::factory()->count(2)->for($userB, 'user')->create();
    }
}

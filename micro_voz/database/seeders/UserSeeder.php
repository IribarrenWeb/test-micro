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
        if (!User::email('keinher@test.com')->exists()) {
            User::factory()->create([
                'name' => 'Keinher',
                'email' => 'keinher@test.com',
            ]);
        }

        User::factory(10)->create();
    }
}

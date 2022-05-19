<?php

namespace Database\Seeders;

use App\Models\TypeClient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeClienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ["name" => "Natural"],
            ["name" => "Empresarial"],
            ["name" => "Otro"],
        ];

        foreach ($roles as $role) {
            TypeClient::updateOrCreate($role);
        }
    }
}

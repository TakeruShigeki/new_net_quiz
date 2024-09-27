<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => '茂木健',
            'email' => 'fruit_ore@icloud.com',
            "email_verified_at" => "2024-02-01 00:00:00",
            'password' => '$2y$10$U8A/lvpDwBRbjo.ijuceLeA2yGYvqv9qRq7/TCHSQsPRVudTccjui',
            "created_at" => "2024-02-01 00:00:00",
            "updated_at" => "2024-02-01 00:00:00",
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::upsert(
            [
                [
                    'name' => 'Fausto Pimentel',
                    'email' => 'faustopimentel@stp-up.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Cliente 1',
                    'email' => 'cliente1@mail.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Admin',
                    'email' => 'admin@mail.com',
                    'password' => Hash::make('power@123'),
                ],
                [
                    'name' => 'User 1',
                    'email' => 'user1@mail.com',
                    'password' => Hash::make('password'),
                ],
            ],
            ['email']
        );
    }
}

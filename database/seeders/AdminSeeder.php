<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin'), // password
            'status' => 1,
        ])->assignRole( 'admin');

        User::create([
            'name' => 'agent',
            'email' => 'agent@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('agent'), // password
            'status' => 1,

        ])->assignRole('agent');



    }
}

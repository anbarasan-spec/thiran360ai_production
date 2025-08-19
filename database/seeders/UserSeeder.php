<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id'          => 1,
            'name'        => 'Admin User',
            'email'       => 'admin@thiran360ai.com',
            'phone_no'    => '1234567890',
            'password'    => Hash::make('password123'),
            'designation' => 'Administrator',
            'salary'      => 0.00,
            'role'        => 'admin',
            'user_status' => 'inactive',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}

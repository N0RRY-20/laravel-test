<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Gurus and Employees
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Guru $i",
                'email' => "guru$i@school.com",
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]);

            DB::table('employees')->insert([
                'user_id' => $user->id,
                'nip' => '19800101202301100' . $i,
                'position' => 'Teacher',
                'status' => 'Permanent',
                'base_salary' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Walis and Parents
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Wali $i",
                'email' => "wali$i@school.com",
                'password' => Hash::make('password'),
                'role' => 'wali',
            ]);

            DB::table('parents')->insert([
                'user_id' => $user->id,
                'phone' => '08123456789' . $i,
                'address' => "Address of Wali $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create Kantin
        User::create([
            'name' => 'Kantin Staff',
            'email' => 'kantin@school.com',
            'password' => Hash::make('password'),
            'role' => 'kantin',
        ]);
    }
}

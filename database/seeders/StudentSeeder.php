<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = DB::table('parents')->pluck('id');
        $classrooms = DB::table('classrooms')->pluck('id');

        if ($parents->isEmpty()) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            DB::table('students')->insert([
                'parent_id' => $parents->random(),
                'classroom_id' => $classrooms->isNotEmpty() ? $classrooms->random() : null,
                'nis' => 'NIS202300' . $i,
                'name' => "Student $i",
                'wallet_balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

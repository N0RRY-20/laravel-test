<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some employees to assign as homeroom teachers
        $employees = DB::table('employees')->pluck('id');

        $classrooms = [
            ['name' => '10-A', 'academic_year' => '2023/2024'],
            ['name' => '10-B', 'academic_year' => '2023/2024'],
            ['name' => '11-A', 'academic_year' => '2023/2024'],
            ['name' => '11-B', 'academic_year' => '2023/2024'],
            ['name' => '12-A', 'academic_year' => '2023/2024'],
        ];

        foreach ($classrooms as $index => $classroom) {
            DB::table('classrooms')->insert([
                'name' => $classroom['name'],
                'academic_year' => $classroom['academic_year'],
                'homeroom_teacher_id' => $employees[$index % count($employees)] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

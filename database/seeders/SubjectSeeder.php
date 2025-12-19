<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101'],
            ['name' => 'Science', 'code' => 'SCI101'],
            ['name' => 'English', 'code' => 'ENG101'],
            ['name' => 'Islamic Studies', 'code' => 'ISL101'],
            ['name' => 'Quran', 'code' => 'QUR101'],
            ['name' => 'Physics', 'code' => 'PHY101'],
            ['name' => 'Chemistry', 'code' => 'CHE101'],
            ['name' => 'Biology', 'code' => 'BIO101'],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'name' => $subject['name'],
                'code' => $subject['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

use App\Course;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Course::create([
            'name' => 'Computer Engineering',
            'program_id' => 1
        ]);

        Course::create([
            'name' => 'Electrical Engineering',
            'program_id' => 1
        ]);

        Course::create([
            'name' => 'Civil Engineering',
            'program_id' => 1
        ]);

        Course::create([
            'name' => 'Computer Science',
            'program_id' => 2
        ]);

        Course::create([
            'name' => 'Biological Science',
            'program_id' => 2
        ]);

        Course::create([
            'name' => 'Social Science',
            'program_id' => 2
        ]);

        Course::create([
            'name' => 'Microbiology',
            'program_id' => 3
        ]);

        Course::create([
            'name' => 'Radiology',
            'program_id' => 3
        ]);

        Course::create([
            'name' => 'Enterpreneship ',
            'program_id' => 4
        ]);


        Course::create([
            'name' => 'Fire & Disaster Management',
            'program_id' => 4
        ]);

        Course::create([
            'name' => 'Natural Resource Management',
            'program_id' => 5
        ]);
    }
}

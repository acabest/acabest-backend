<?php

use App\Program;
use Illuminate\Database\Seeder;

class ProgramsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Program::create([
            'name' => 'Engineering',
        ]);

        Program::create([
            'name' => 'Science',
        ]);

        Program::create([
            'name' => 'Health',
        ]);

        Program::create([
            'name' => 'Humanities',
        ]);

        Program::create([
            'name' => 'Natural Resource',
        ]);
    }
}

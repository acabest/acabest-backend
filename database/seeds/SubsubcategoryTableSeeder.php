<?php

use Illuminate\Database\Seeder;

class SubsubcategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Subsubcategory::create([
            'name' => 'Computer Architecture',
            'subcategory_id' => 1
        ]);

        \App\Subsubcategory::create([
            'name' => 'Thermodynamics',
            'subcategory_id' => 2
        ]);

        \App\Subsubcategory::create([
            'name' => 'Mobile Computing',
            'subcategory_id' => 3
        ]);

        \App\Subsubcategory::create([
            'name' => 'Anatomy',
            'subcategory_id' => 4
        ]);
    }
}

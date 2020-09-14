<?php

use Illuminate\Database\Seeder;

class SubcategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Subcategory::create([
            'name' => 'Computer Engineering',
            'category_id' => 1
        ]);

        \App\Subcategory::create([
            'name' => 'Mechanical Engineering',
            'category_id'  => 1
        ]);

        \App\Subcategory::create([
            'name' => 'Computer Science',
            'category_id' => 2
        ]);

        \App\Subcategory::create([
            'name' => 'Biological Science',
            'category_id' => 2
        ]);
    }
}

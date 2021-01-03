<?php

use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('color_tbl')->insert([
            'color_name' => Str::random(10),
            'color_status' => '1'
        ]);
    }
}

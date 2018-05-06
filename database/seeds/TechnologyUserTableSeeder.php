<?php

use Illuminate\Database\Seeder;

class TechnologyUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //team lead
        DB::table('technology_user')->insert([
            'user_id' => 5,
            'technology_id' => 1,
        ]);

        DB::table('technology_user')->insert([
            'user_id' => 5,
            'technology_id' => 2,
        ]);

        DB::table('technology_user')->insert([
            'user_id' => 5,
            'technology_id' => 3,
        ]);

        DB::table('technology_user')->insert([
            'user_id' => 5,
            'technology_id' => 4,
        ]);

        DB::table('technology_user')->insert([
            'user_id' => 5,
            'technology_id' => 5,
        ]);

        //developer
        DB::table('technology_user')->insert([
            'user_id' => 4,
            'technology_id' => 1,
        ]);

        DB::table('technology_user')->insert([
            'user_id' => 4,
            'technology_id' => 3,
        ]);
    }
}

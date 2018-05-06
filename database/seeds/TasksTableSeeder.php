<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            'name' => "task1",
            'project_id' => 1,
            'description' => "create db",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('tasks')->insert([
            'name' => "task2",
            'project_id' => 1,
            'description' => "write a lot of code",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('tasks')->insert([
            'name' => "task1",
            'project_id' => 2,
            'description' => "edit layout",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('tasks')->insert([
            'name' => "task2",
            'project_id' => 2,
            'description' => "add imgs",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}

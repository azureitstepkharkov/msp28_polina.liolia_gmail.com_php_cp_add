<?php

use Illuminate\Database\Seeder;

class TaskCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comment_task')->insert([
            'task_id' => 1,
            'comment' => "Some strange comment...",
            'author_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('comment_task')->insert([
            'task_id' => 2,
            'comment' => "Some silly comment...",
            'author_id' => 4,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('comment_task')->insert([
            'task_id' => 3,
            'comment' => "Some important comment...",
            'author_id' => 3,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}

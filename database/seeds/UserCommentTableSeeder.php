<?php

use Illuminate\Database\Seeder;

class UserCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comment_user')->insert([
            'user_id' => 2,
            'comment' => "Very good client! Pays in time.",
            'author_id' => 3,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('comment_user')->insert([
            'user_id' => 4,
            'comment' => "Very responsible dev!",
            'author_id' => 5,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}

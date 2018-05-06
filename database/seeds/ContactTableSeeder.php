<?php

use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->insert([
            'type_id' => 1,
            'value'=>"+380502178569",
            'user_id' => 1
        ]);

        DB::table('contacts')->insert([
            'type_id' => 1,
            'value'=>"+380687412589",
            'user_id' => 1
        ]);

        DB::table('contacts')->insert([
            'type_id' => 4,
            'value'=>"skypeclient123",
            'user_id' => 2
        ]);

        DB::table('contacts')->insert([
            'type_id' => 5,
            'value'=>"+380678523698",
            'user_id' => 3
        ]);

        DB::table('contacts')->insert([
            'type_id' => 3,
            'value'=>"client___VIP@mail.ru",
            'user_id' => 3
        ]);
    }
}

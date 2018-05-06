<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "User_admin",
            'email' => "user_admin@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => "User_client",
            'email' => "User_client@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => "User_pm",
            'email' => "User_pm@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => "User_dev",
            'email' => "User_dev@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => "User_tl",
            'email' => "User_tl@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);

        DB::table('users')->insert([
            'name' => "super",
            'email' => "super@gmail.com",
            'password' => bcrypt("123456"),
            'status' => "active",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);


    }
}

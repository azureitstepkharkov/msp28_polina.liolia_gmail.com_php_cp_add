<?php

use Illuminate\Database\Seeder;

class ContactTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_types')->insert([
            'contact_type' => "Phone"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "Fax"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "Email"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "Skype"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "Viber"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "WhatsApp"
        ]);

        DB::table('contact_types')->insert([
            'contact_type' => "Telegram"
        ]);
    }
}

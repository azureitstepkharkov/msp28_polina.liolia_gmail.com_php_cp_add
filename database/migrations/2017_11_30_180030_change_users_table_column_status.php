<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTableColumnStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        //изменение статуса по умолчанию
        //добавление новых пользователей с новым статусом 'active'
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'active';");

        //или если необходимо (только создать в новом файле миграции)
        //удаляю колонку статус и добавляю с нужным статусом -
        // во всех записях базы статус изменился автоматически
//        if(Schema::hasTable('users')) {
//            if (Schema::hasColumn('users', 'status'))
//            {
//                Schema::table('users', function (Blueprint $table) {
//                    $table->dropColumn('status');
//                });
//            }
//            Schema::table('users', function (Blueprint $table) {
//                    $table->enum('status',['active', 'inactive'])->default('active');
//            });
//        }
//        ->change()
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active', 'inactive') NOT NULL DEFAULT 'inactive';");
    }
}

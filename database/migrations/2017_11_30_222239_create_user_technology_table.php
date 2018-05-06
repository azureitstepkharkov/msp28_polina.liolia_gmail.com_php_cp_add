<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTechnologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('technology_user')) {
            Schema::create('technology_user', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('technology_id')->unsigned();

                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('technology_id')->references('id')->on('technologies')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->primary(['user_id', 'technology_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technology_user');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTechnologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('project_technology')) {
            Schema::create('project_technology', function (Blueprint $table) {
                $table->integer('project_id')->unsigned();
                $table->integer('technology_id')->unsigned();
                $table->primary(['project_id', 'technology_id']);
                $table->foreign('project_id')->references('id')->on('projects')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('technology_id')->references('id')->on('technologies')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->timestamps();
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
        Schema::dropIfExists('project_technology');
    }
}

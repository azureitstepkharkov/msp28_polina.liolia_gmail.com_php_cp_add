<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->integer('client_id')->unsigned();
                $table->foreign('client_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->integer('project_manager_id')->unsigned();
                $table->foreign('project_manager_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->enum('status', ['in_work', 'completed', 'canceled'])->default('in_work');
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
        Schema::dropIfExists('projects');
    }
}

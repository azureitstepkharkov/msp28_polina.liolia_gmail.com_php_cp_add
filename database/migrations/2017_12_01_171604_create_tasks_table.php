<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Carbon\Carbon;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->integer('project_id')->unsigned();
                $table->foreign('project_id')->references('id')->on('projects')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->dateTime('start')->default(Carbon::now());
                $table->dateTime('end')->default(Carbon::now());
                $table->enum('status', ['new', 'in_progress', 'completed', 'canceled'])->default('new');
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
        Schema::dropIfExists('tasks');
    }
}

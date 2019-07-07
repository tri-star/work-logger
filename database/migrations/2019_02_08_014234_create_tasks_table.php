<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedInteger('user_id');
            $table->string('issue_no', 50)->default('');
            $table->string('title', 50)->default('');
            $table->string('description', 255)->default('');
            $table->smallInteger('estimate_minutes')->default(0);
            $table->smallInteger('actual_minutes')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->date('start_date')->nullable()->default(null);
            $table->date('end_date')->nullable()->default(null);
            $table->dateTime('completed_at')->nullable()->default(null);
            $table->timestamps();


            $table->index(['project_id']);
            $table->index(['start_date']);
            $table->index(['end_date']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
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

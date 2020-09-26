<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_log', function (Blueprint $table) {
            $table->id();
            $table->enum('action', ['ADD', 'CHANGE', 'DELETE']);
            $table->enum('type', ['ITEM', 'GROUP', 'SHARE', 'SETTINGS', 'USERS']);
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('project_id', 'log_project_id')->references('id')->on('projects')
                ->onDelete('cascade');
            $table->foreign('user_id', 'log_user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('item_id', 'log_item_id')->references('id')->on('items')
                ->onDelete('cascade');
            $table->foreign('group_id', 'log_group_id')->references('id')->on('groups')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_log');
    }
}

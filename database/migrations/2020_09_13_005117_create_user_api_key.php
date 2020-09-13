<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApiKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_api_key', function (Blueprint $table) {
            $table->bigInteger('project_id')->unsigned();
            $table->uuid('api_key_id');
            $table->foreign('project_id', 'apikey_project_id')->references('id')->on('projects')
                ->onDelete('cascade');
            $table->foreign('api_key_id', 'pkey_api_id')->references('id')->on('api_keys')
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
        Schema::dropIfExists('project_api_key');
    }
}

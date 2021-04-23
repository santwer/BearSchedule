<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJiraIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['JIRA'])->default('JIRA');
            $table->string('key');
            $table->bigInteger('item_id')->unsigned();
            $table->integer('process')->nullable();
            $table->integer('done')->nullable();
            $table->integer('estimate')->nullable();
            $table->timestamps();

            $table->foreign('item_id', 'item_id_issue')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
}

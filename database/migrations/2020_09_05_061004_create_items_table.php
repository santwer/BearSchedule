<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content')->nullable();
            $table->string('className')->nullable();
            $table->string('style')->nullable();
            $table->string('align')->nullable();
            $table->dateTime('end')->nullable();
            $table->dateTime('start');
            $table->unsignedBigInteger('group')->nullable();
            $table->boolean('selectable')->default(true);
            $table->string('subgroup')->nullable();
            $table->string('type')->nullable();
            $table->boolean('limitSize')->nullable();
            $table->boolean('editable')->nullable();


            $table->timestamps();

            $table->foreign('group')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}

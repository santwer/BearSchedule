<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemItemLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_item_link', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned();
            $table->bigInteger('item_link_id')->unsigned();
            $table->foreign('item_id', 'key_itemid')->references('id')->on('items')

                ->onDelete('cascade');

            $table->foreign('item_link_id', 'key_linkid')->references('id')->on('item_links')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_item_links');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->string('className')->nullable();
            $table->string('style')->nullable();
            $table->longText('subgroupStack')->nullable();
            $table->longText('subgroupVisibility')->nullable();
            $table->boolean('visible');
            $table->smallInteger('treeLevel')->nullable();
            $table->longText('nestedGroups')->nullable();
            $table->boolean('showNested')->nullable();
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
        Schema::dropIfExists('groups');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_local')->nullable();
            $table->string('language')->nullable();
            $table->string('description')->nullable();
            $table->char('country', 5)->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();
            $table->date('date')->nullable();
            $table->integer('date_year')->nullable();
            $table->integer('date_month')->nullable();
            $table->integer('date_day')->nullable();
            $table->string('week_day')->nullable();
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
        Schema::dropIfExists('holidays');
    }
}

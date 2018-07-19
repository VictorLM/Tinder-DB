<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SearchLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('at', 255)->nullable();
            $table->string('lat', 255)->nullable();
            $table->string('lon', 255)->nullable();
            $table->text('pos')->nullable();
            $table->text('pos_info')->nullable();
            $table->text('complete_profile')->nullable();
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
        Schema::dropIfExists('search_locations');
    }
}

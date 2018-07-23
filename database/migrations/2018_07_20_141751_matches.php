<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Matches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('match_id', 255)->nullable();
            $table->unsignedInteger('logged_profile_id')->nullable()->default(null);
            $table->foreign('logged_profile_id')->references('id')->on('logged_profiles');
            $table->unsignedInteger('profile_id')->nullable()->default(null);
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->dateTime('date')->nullable();
            $table->string('full_match_info', 1000)->nullable()->default(null);
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
        Schema::dropIfExists('matches');
    }
}

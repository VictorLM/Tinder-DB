<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('super_like_id', 255)->nullable()->default(null);
            $table->unsignedInteger('logged_profile_id')->nullable()->default(null);
            $table->foreign('logged_profile_id')->references('id')->on('logged_profiles');
            $table->unsignedInteger('profile_id')->nullable()->default(null);
            $table->foreign('profile_id')->references('id')->on('profiles');
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
        Schema::dropIfExists('super_likes');
    }
}

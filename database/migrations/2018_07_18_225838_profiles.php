<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Profiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('logged_profile_id')->nullable()->default(null);
            $table->foreign('logged_profile_id')->references('id')->on('logged_profiles');
            $table->string('tinder_id', 255)->nullable()->default(null);
            $table->boolean('group_matched')->nullable()->default(null);
            $table->integer('distance_mi')->nullable()->default(null);
            $table->string('content_hash', 255)->nullable()->default(null);
            $table->string('common_friends', 255)->nullable()->default(null);
            $table->string('common_likes', 255)->nullable()->default(null);
            $table->integer('common_friend_count')->nullable()->default(null);
            $table->integer('common_like_count')->nullable()->default(null);
            $table->integer('connection_count')->nullable()->default(null);
            $table->string('bio', 1000)->nullable()->default(null);
            $table->dateTime('birth_date')->nullable()->default(null);
            $table->string('name', 255)->nullable()->default(null);
            $table->dateTime('ping_time')->nullable()->default(null);
            $table->string('photos', 2000)->nullable()->default(null);
            $table->string('instagram', 100)->nullable()->default(null);
            $table->string('spotify', 300)->nullable()->default(null);
            $table->string('jobs', 255)->nullable()->default(null);
            $table->string('schools', 255)->nullable()->default(null);
            $table->string('teasers', 300)->nullable()->default(null);
            $table->integer('gender')->nullable()->default(null);
            $table->string('birth_date_info', 255)->nullable()->default(null);
            $table->string('s_number', 100)->nullable()->default(null);
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
        Schema::dropIfExists('profiles');
    }
}

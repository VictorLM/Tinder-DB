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
            $table->text('common_friends')->nullable()->default(null);
            $table->text('common_likes')->nullable()->default(null);
            $table->integer('common_friend_count')->nullable()->default(null);
            $table->integer('common_like_count')->nullable()->default(null);
            $table->integer('connection_count')->nullable()->default(null);
            $table->text('bio')->nullable()->default(null);
            $table->dateTime('birth_date')->nullable()->default(null);
            $table->string('name', 255)->nullable()->default(null);
            $table->dateTime('ping_time')->nullable()->default(null);
            $table->text('photos')->nullable()->default(null);
            $table->text('instagram')->nullable()->default(null);
            $table->text('spotify_theme_track')->nullable()->default(null);
            $table->text('jobs')->nullable()->default(null);
            $table->text('schools')->nullable()->default(null);
            $table->text('teaser')->nullable()->default(null);
            $table->text('teasers')->nullable()->default(null);
            $table->integer('gender')->nullable()->default(null);
            $table->string('birth_date_info', 255)->nullable()->default(null);
            $table->string('s_number', 255)->nullable()->default(null);
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

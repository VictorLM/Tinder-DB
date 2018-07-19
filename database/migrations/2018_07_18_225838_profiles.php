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
            $table->unsignedInteger('search_location_id')->nullable()->default(null);
            $table->foreign('search_location_id')->references('id')->on('search_locations');
            $table->string('tinder_id', 255)->nullable();
            $table->boolean('group_matched')->nullable();
            $table->integer('distance_mi')->nullable();
            $table->string('content_hash', 255)->nullable();
            $table->text('common_friends')->nullable();
            $table->text('common_likes')->nullable();
            $table->integer('common_friend_count')->nullable();
            $table->integer('common_like_count')->nullable();
            $table->integer('connection_count')->nullable();
            $table->text('bio')->nullable();
            $table->timestamp('birth_date')->nullable();
            $table->string('name', 255)->nullable();
            $table->timestamp('ping_time')->nullable();
            $table->text('photos')->nullable();
            $table->text('instagram')->nullable();
            $table->text('jobs')->nullable();
            $table->text('schools')->nullable();
            $table->text('teaser')->nullable();
            $table->text('teasers')->nullable();
            $table->integer('gender')->nullable();
            $table->string('birth_date_info', 255)->nullable();
            $table->string('s_number', 255)->nullable();
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

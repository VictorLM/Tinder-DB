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
            $table->boolean('closed')->nullable()->default(null);
            $table->integer('common_friend_count')->nullable()->default(null);
            $table->integer('common_like_count')->nullable()->default(null);
            $table->boolean('dead')->nullable()->default(null);
            $table->dateTime('last_activity_date')->nullable()->default(null);
            $table->integer('message_count')->nullable()->default(null);
            $table->boolean('muted')->nullable()->default(null);
            $table->string('participants', 500)->nullable()->default(null);//JSON
            $table->boolean('pending')->nullable()->default(null);
            $table->boolean('is_super_like')->nullable()->default(null);
            $table->boolean('is_boost_match')->nullable()->default(null);
            $table->boolean('is_fast_match')->nullable()->default(null);
            $table->boolean('following')->nullable()->default(null);
            $table->boolean('following_moments')->nullable()->default(null);
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoggedProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logged_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tinder_id', 255)->nullable()->default(null);
            $table->integer('age_filter_max')->nullable()->default(null);
            $table->integer('age_filter_min')->nullable()->default(null);
            $table->string('bio', 1000)->nullable()->default(null);
            $table->dateTime('birth_date')->nullable()->default(null);
            $table->dateTime('create_date')->nullable()->default(null);
            $table->integer('distance_filter')->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('facebook_id', 100)->nullable()->default(null);
            $table->integer('gender')->nullable()->default(null);
            $table->integer('gender_filter')->nullable()->default(null);
            $table->string('interested_in')->nullable()->default(null);
            $table->string('name', 100)->nullable()->default(null);
            $table->string('photos', 1000)->nullable()->default(null);
            $table->string('instagram', 100)->nullable()->default(null);
            $table->string('spotify', 255)->nullable()->default(null);
            $table->dateTime('ping_time')->nullable()->default(null);
            $table->string('at', 100)->nullable()->default(null);
            $table->string('lat', 100)->nullable()->default(null);
            $table->string('lon', 100)->nullable()->default(null);
            $table->string('city', 100)->nullable()->default(null);
            $table->string('country', 100)->nullable()->default(null);
            $table->string('full_pos_info', 1000)->nullable()->default(null);
            $table->boolean('show_gender_on_profile')->nullable()->default(null);
            $table->boolean('can_create_squad')->nullable()->default(null);
            $table->string('access_token', 100)->nullable()->default(null);
            $table->dateTime('access_token_get_at')->nullable()->default(null);
            $table->boolean('bot')->nullable()->default(null);
            $table->string('IP', 100)->nullable()->default(null);
            $table->string('user_agent', 255)->nullable()->default(null);
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
        Schema::dropIfExists('logged_profiles');
    }
}

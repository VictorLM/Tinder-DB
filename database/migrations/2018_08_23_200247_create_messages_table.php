<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id', 255)->nullable();
            $table->string('match_id', 255)->nullable();
            $table->unsignedInteger('local_match_id')->nullable()->default(null);
            $table->foreign('local_match_id')->references('id')->on('matches');
            $table->dateTime('sent_date')->nullable()->default(null);
            $table->string('message', 1000)->nullable();
            $table->string('to', 255)->nullable();
            $table->string('from', 255)->nullable();
            $table->dateTime('created_date')->nullable()->default(null);
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
        Schema::dropIfExists('messages');
    }
}

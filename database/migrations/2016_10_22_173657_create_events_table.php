<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('status_id');
            $table->unsignedInteger('venue_id');
            $table->string('title', 100);
            $table->string('description', 255);
            $table->timestamp('start_at')->nullable();
            //$table->date('start_date')->nullable();
            //$table->time('start_time')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['title', 'description']);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('event_types');
            $table->foreign('status_id')->references('id')->on('event_statuses');
            $table->foreign('venue_id')->references('id')->on('event_venues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

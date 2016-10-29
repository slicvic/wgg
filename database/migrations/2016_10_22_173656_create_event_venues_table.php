<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_venues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->default('');
            $table->string('address', 255)->default('');
            $table->string('url', 255)->default('');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->timestamps();
        });

        //DB::statement('ALTER TABLE event_venues ADD COLUMN latlng POINT AFTER address');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_venues');
    }
}

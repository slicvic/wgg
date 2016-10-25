<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('social_account_id', 255)->nullable();
            $table->unsignedInteger('social_account_type_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('location_name', 255)->nullable();
            $table->unsignedTinyInteger('active')->default(0);
            $table->timestamp('loggedin_at')->nullable();
            $table->timestamps();

            $table->index(['social_account_id']);
            $table->unique(['social_account_id', 'social_account_type_id']);
            $table->foreign('social_account_type_id')->references('id')->on('social_account_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

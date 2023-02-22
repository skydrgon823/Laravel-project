<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_header', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('object');
            $table->string('description')->nullable();
            $table->integer('is_urgent')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->string('owner_type');
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
        Schema::dropIfExists('message_header');
    }
};

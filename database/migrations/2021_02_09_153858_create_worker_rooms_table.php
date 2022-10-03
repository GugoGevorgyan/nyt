<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_rooms', function (Blueprint $table) {
            $table->increments('worker_room_id')->index('index_worker_rooms_worker_room_id');
            $table->unsignedInteger('room_id')->index('index_worker_rooms_room_id');
            $table->unsignedInteger('system_worker_id')->index('index_worker_rooms_system_worker_id');
            $table->boolean('archived')->default(false);
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
        Schema::dropIfExists('worker_rooms');
    }
}

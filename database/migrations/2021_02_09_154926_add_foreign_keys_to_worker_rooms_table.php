<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWorkerRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worker_rooms', function (Blueprint $table) {
            $table
                ->foreign('room_id', 'worker_rooms_foreign_room_id')
                ->references('room_id')
                ->on('rooms')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table
                ->foreign('system_worker_id', 'worker_rooms_foreign_system_worker_id')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_rooms', function (Blueprint $table) {
            $table->dropForeign('worker_rooms_foreign_room_id');
            $table->dropForeign('worker_rooms_foreign_system_worker_id');
        });
    }
}

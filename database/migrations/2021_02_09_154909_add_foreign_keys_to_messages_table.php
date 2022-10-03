<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table
                ->foreign('room_id', 'messages_foreign_room_id')
                ->references('room_id')
                ->on('rooms')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table
                ->foreign('sender_id', 'messages_foreign_sender_id')
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
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_foreign_room_id');
            $table->dropForeign('messages_foreign_sender_id');
        });
    }
}

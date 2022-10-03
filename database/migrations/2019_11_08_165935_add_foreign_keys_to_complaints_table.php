<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign('franchise_id',
                'complaints_franchise_id')->references('franchise_id')->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('order_id',
                'complaints_order_id')->references('order_id')->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('writer_id',
                'complaints_writer_id')->references('system_worker_id')->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('recipient_id',
                'complaints_recipient_id')->references('system_worker_id')->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('status_id',
                'complaints_status_id')->references('complaint_status_id')->on('complaint_statuses')
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
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('complaints_franchise_id');
            $table->dropForeign('complaints_order_id');
            $table->dropForeign('complaints_writer_id');
            $table->dropForeign('complaints_recipient_id');
            $table->dropForeign('complaints_status_id');
        });
    }
}

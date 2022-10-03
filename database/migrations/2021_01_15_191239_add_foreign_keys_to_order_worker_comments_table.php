<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderWorkerCommentsTable
 */
class AddForeignKeysToOrderWorkerCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_worker_comments', function (Blueprint $table) {
            $table
                ->foreign('order_id', 'order_worker_comments_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table
                ->foreign('system_worker_id', 'order_worker_comments_foreign_system_worker_id')
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
        Schema::table('order_worker_comments', function (Blueprint $table) {
            $table->dropForeign('order_worker_comments_foreign_order_id');
            $table->dropForeign('order_worker_comments_foreign_system_worker_id');
        });
    }
}

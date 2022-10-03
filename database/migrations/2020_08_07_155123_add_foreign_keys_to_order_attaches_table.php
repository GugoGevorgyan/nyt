<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderAttachesTable
 */
class AddForeignKeysToOrderAttachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_attaches', static function (Blueprint $table) {
            $table->foreign('order_id', 'order_attaches_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
            $table->foreign('driver_id', 'order_attaches_foreign_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
            $table->foreign('system_worker_id', 'order_attaches_foreign_system_worker_id')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
            $table->foreign('shipped_id', 'order_attaches_foreign_shipped_id')
                ->references('order_shipped_driver_id')
                ->on('order_shipped_drivers')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_attaches', function (Blueprint $table) {
            $table->dropForeign('order_attaches_foreign_order_id');
            $table->dropForeign('order_attaches_foreign_driver_id');
            $table->dropForeign('order_attaches_foreign_system_worker_id');
            $table->dropForeign('order_attaches_foreign_shipped_id');
        });
    }
}

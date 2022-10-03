<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderInProcessRoadsTable
 */
class AddForeignKeysToOrderInProcessRoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_in_process_roads', static function (Blueprint $table) {
            $table->foreign('shipment_driver_id', 'order_in_process_roads_shipment_driver_id')
                ->references('order_shipped_driver_id')
                ->on('order_shipped_drivers')
                ->onUpdate('CASCADE')
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
        Schema::table('order_in_process_roads', static function (Blueprint $table) {
            $table->dropForeign('order_in_process_roads_shipment_driver_id');
        });
    }
}

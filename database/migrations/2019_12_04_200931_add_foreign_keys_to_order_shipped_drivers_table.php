<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderShippedDriversTable
 */
class AddForeignKeysToOrderShippedDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_shipped_drivers', static function (Blueprint $table) {
            $table->foreign('driver_id', 'ordering_shipment_drivers_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('order_id', 'ordering_shipment_driver_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('estimated_rating_id', 'ordering_shipment_estimated_rating_id')
                ->references('estimated_rating_id')
                ->on('estimated_ratings')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('status_id', 'ordering_shipment_driver_status_id')
                ->references('order_shipped_status_id')
                ->on('order_shipped_status')
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
        Schema::table('order_shipped_drivers', static function (Blueprint $table) {
            $table->dropForeign('ordering_shipment_drivers_driver_id');
            $table->dropForeign('ordering_shipment_drivers_order_id');
            $table->dropForeign('ordering_shipment_estimated_rating_id');
            $table->dropForeign('ordering_shipment_drivers_status_id');
        });
    }
}

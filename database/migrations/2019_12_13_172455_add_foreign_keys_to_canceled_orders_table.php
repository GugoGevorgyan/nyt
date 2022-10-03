<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCanceledOrdersTable
 */
class AddForeignKeysToCanceledOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('canceled_orders', static function (Blueprint $table) {
            $table->foreign('order_id', 'canceled_orders_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('driver_id', 'canceled_orders_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('car_id', 'canceled_orders_car_id')
                ->references('car_id')
                ->on('cars')
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
        Schema::table('canceled_orders', static function (Blueprint $table) {
            $table->dropForeign('canceled_orders_order_id');
            $table->dropForeign('canceled_orders_driver_id');
            $table->dropForeign('canceled_orders_car_id');
        });
    }
}

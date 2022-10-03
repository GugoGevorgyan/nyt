<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCompletedOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('completed_orders', function (Blueprint $table) {

            $table->foreign('order_id', 'completed_orders_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('driver_id', 'completed_orders_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('car_id', 'completed_orders_car_id')
                ->references('car_id')
                ->on('cars')
                ->onUpdate('NO ACTION')
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
        Schema::table('completed_orders', function (Blueprint $table) {
            $table->dropForeign('completed_orders_order_id');
            $table->dropForeign('completed_orders_driver_id');
            $table->dropForeign('completed_orders_car_id');
        });
    }
}

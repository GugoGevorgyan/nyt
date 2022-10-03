<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToOrdersTable
 */
class AddForeignKeysToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->foreign('car_class_id', 'orders_foreign_car_class_id')
                    ->references('car_class_id')
                    ->on('cars_class')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');

                $table->foreign('client_id', 'orders_foreign_client_id')
                    ->references('client_id')
                    ->on('clients')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');

                $table->foreign('order_type_id', 'orders_foreign_order_type_id')
                    ->references('order_type_id')
                    ->on('order_types')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');

                $table->foreign('payment_type_id', 'orders_foreign_payment_type_id')
                    ->references('payment_type_id')
                    ->on('payment_types')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');

                $table->foreign('status_id', 'orders_foreign_status_id')
                    ->references('order_status_id')
                    ->on('order_statuses')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');

                $table->foreign('passenger_id', 'orders_foreign_passenger_id')
                    ->references('client_id')
                    ->on('clients')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');

                $table->foreign('operator_id', 'orders_foreign_operator_id')
                    ->references('system_worker_id')
                    ->on('system_workers')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->dropForeign('orders_foreign_car_class_id');
                $table->dropForeign('orders_foreign_client_id');
                $table->dropForeign('orders_foreign_order_type_id');
                $table->dropForeign('orders_foreign_payment_type_id');
                $table->dropForeign('orders_foreign_orders_status_id');
                $table->dropForeign('orders_foreign_passenger_id');
                $table->dropForeign('orders_foreign_operator_id');
            }
        );
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Src\Core\Enums\ConstOrderDistType;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'orders',
            static function (Blueprint $table) {
                $table->increments('order_id')->index('index_order_order_id_index');

                $table->unsignedInteger('car_class_id')->nullable()->index('index_orders_car_class_id');
                $table->unsignedInteger('order_type_id')->nullable()->index('index_orders_order_type_id');
                $table->unsignedInteger('payment_type_id')->nullable()->index('index_orders_payment_type_id');
                $table->unsignedInteger('status_id')->nullable()->index('index_orders_worker_status_id');
                $table->unsignedInteger('client_id')->nullable()->index('index_orders_client_id');
                $table->unsignedInteger('passenger_id')->nullable()->index('index_orders_passenger_id');
                $table->unsignedInteger('operator_id')->nullable()->index('index_orders_operator_id');
                $table->unsignedInteger('customer_zone_id')->nullable()->index('index_orders_customer_zone_id');
                $table->unsignedInteger('location_zone_id')->nullable()->index('index_orders_location_zone_id');
                $table->unsignedInteger('customer_id')->nullable();
                $table->string('customer_type')->nullable();

                $table->json('franchisee')->nullable();
                $table->json('car_option')->nullable();

                $table->json('from_coordinates');
                $table->json('to_coordinates')->nullable();
                $table->string('address_from', 300);
                $table->string('address_to', 300)->nullable();
                $table->boolean('show_cord')->default(0);
                $table->decimal('lat', 10, 8)->nullable();
                $table->decimal('lut', 11, 8)->nullable();

                $table->string('platform', 50)->nullable();
                $table->text('comments')->nullable();
                $table->unsignedTinyInteger('dist_type')->default(1);

                $table->softDeletes();
                $table->timestamps(6);
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
        Schema::drop('orders');
    }

}

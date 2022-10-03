<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCompletedOrdersTable
 */
class CreateCompletedOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('completed_orders', static function (Blueprint $table) {
            $table->increments('completed_order_id')->index('index_completed_order_id');
            $table->unsignedInteger('order_id')->nullable()->index('completed_orders_order_id');
            $table->unsignedInteger('driver_id')->nullable()->index('completed_orders_driver_id');
            $table->unsignedInteger('car_id')->nullable()->index('completed_orders_car_id');
            $table->string('destination_address', 250)->nullable();
            $table->decimal('destination_lat', 10,8)->nullable();
            $table->decimal('destination_lut', 11,8)->nullable();
            $table->decimal('cost')->nullable();
            $table->decimal('distance_price')->default(0);
            $table->decimal('duration_price')->default(0);
            $table->unsignedFloat('distance', 5, 1)->nullable();
            $table->unsignedSmallInteger('duration')->nullable();
            $table->json('trajectory');
            $table->boolean('changed')->default(0);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('completed_orders');
    }
}

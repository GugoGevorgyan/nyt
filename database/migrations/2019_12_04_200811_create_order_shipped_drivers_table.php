<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Models\Order\OrderShippedStatus;

/**
 * Class CreateDriverPreOrderDatumTable
 */
class CreateOrderShippedDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_shipped_drivers',
            static function (Blueprint $table) {
                $table->increments('order_shipped_driver_id')->index('index_ordering_shipment_driver_id');
                $table->unsignedInteger('driver_id')->index('ordering_shipment_drivers_driver_id');
                $table->unsignedInteger('order_id')->index('ordering_shipment_drivers_order_id');
                $table->unsignedInteger('estimated_rating_id')->nullable()->index('ordering_shipment_estimated_rating_id');
                $table->unsignedInteger('status_id')->nullable()->default(OrderShippedStatus::PRE_PENDING)->index('ordering_shipment_drivers_statuses_id');
                $table->boolean('current');
                $table->boolean('common')->default(false);
                $table->unsignedTinyInteger('late')->default(0)->comment('minutes');

                $table->char('accept_hash', 32)->index('ordering_shipment_index_accept_hash')->nullable();
                $table->char('on_way_hash', 32)->index('ordering_shipment_index_on_way_hash_hash')->nullable();
                $table->char('in_place_hash', 32)->index('ordering_shipment_index_in_place_hash')->nullable();
                $table->char('in_order_hash', 32)->index('ordering_shipment_index_in_order_hash')->nullable();
                $table->char('pause_hash', 32)->index('ordering_shipment_index_pause_hash')->nullable();
                $table->char('end_hash', 32)->index('ordering_shipment_index_end_hash')->nullable();

                $table->timestamps(6);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ordering_shipment_drivers');
    }
}

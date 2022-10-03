<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderOnWayRoadsTable
 */
class CreateOrderOnWayRoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'order_on_way_roads',
            static function (Blueprint $table) {
                $table->increments('order_on_way_road_id')->index('index_order_on_way_road_id');
                $table->unsignedInteger('shipment_driver_id');
                $table->json('route')->nullable();
                $table->unsignedFloat('distance', 5, 1)->nullable();
                $table->unsignedSmallInteger('duration')->nullable();
                $table->json('real_road')->nullable();
                $table->boolean('selected')->default(0)->nullable();

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
        Schema::dropIfExists('order_on_way_roads');
    }
}

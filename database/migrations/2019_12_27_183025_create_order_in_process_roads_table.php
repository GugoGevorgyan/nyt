<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderInProcessRoadsTable
 */
class CreateOrderInProcessRoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'order_in_process_roads',
            static function (Blueprint $table) {
                $table->increments('order_in_process_road_id')->index('index_order_in_process_road_id');
                $table->json('route')->nullable();
                $table->unsignedInteger('shipment_driver_id');
                $table->unsignedFloat('distance',5,1)->nullable();
                $table->unsignedSmallInteger('duration')->nullable();
                $table->boolean('selected')->default(0)->default(0);
                $table->json('real_road')->nullable();

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
        Schema::dropIfExists('order_in_process_roads');
    }
}

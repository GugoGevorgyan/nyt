<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderShippedStatusTable
 */
class CreateOrderShippedStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipped_status', static function (Blueprint $table) {
            $table->increments('order_shipped_status_id')->index('index_ordering_shipment_driver_status_id');
            $table->tinyInteger('status');
            $table->char('name', 30);
            $table->string('text');
            $table->char('color', 15);
            $table->string('description', 500);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordering_shipment_drivers_statuses');
    }
}

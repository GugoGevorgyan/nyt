<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCanceledOrdersTable
 */
class CreateCanceledOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'canceled_orders',
            static function (Blueprint $table) {
                $table->increments('canceled_order_id')->index('index_canceled_order_id');
                $table->unsignedInteger('order_id');
                $table->unsignedInteger('driver_id')->nullable();
                $table->unsignedInteger('car_id')->nullable();
                $table->unsignedInteger('cancelable_id')->index('index_canceled_cancelable_id')->nullable();
                $table->string('cancelable_type', 120)->index('index_canceled_cancelable_typr')->nullable();

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
        Schema::dropIfExists('canceled_orders');
    }
}

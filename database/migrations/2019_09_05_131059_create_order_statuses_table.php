<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateOrderStatusesTable
 */
class CreateOrderStatusesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->increments('order_status_id')->index('index_order_status_id');
            $table->tinyInteger('status');
            $table->string('name', 200);
            $table->string('text');
            $table->string('color');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_statuses');
    }

}

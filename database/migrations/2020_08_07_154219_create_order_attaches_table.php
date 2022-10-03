<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderAttachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_attaches', function (Blueprint $table) {
            $table->increments('order_attach_id');
            $table->unsignedInteger('order_id')->index('index_order_attaches_order_id');
            $table->unsignedInteger('driver_id')->index('index_order_attaches_driver_id');
            $table->unsignedInteger('system_worker_id')->nullable()->index('index_order_attaches_system_worker_id');
            $table->unsignedInteger('shipped_id')->nullable()->index('index_order_attaches_shipped_id');
            $table->boolean('accepted')->default(false);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_attaches');
    }
}

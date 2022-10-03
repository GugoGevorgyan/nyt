<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateExternalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('external_orders', function (Blueprint $table) {
            $table->increments('external_order_id')->index('external_orders_id');
            $table->unsignedInteger('order_id')->index('external_orders_order_id');
            $table->unsignedInteger('board_id')->index('external_orders_board_id');
            $table->char('order_key',32)->index('external_orders_order_key');
            $table->boolean('draft')->default(1);
            $table->json('payload');
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
        Schema::dropIfExists('external_orders');
    }
}

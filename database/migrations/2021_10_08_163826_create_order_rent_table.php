<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateOrderRentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_rent', function (Blueprint $table) {
            $table->increments('order_rent_id')->index('order_rent_rent_id');
            $table->unsignedInteger('order_id')->index('order_rent_order_id');

            $table->unsignedTinyInteger('hours');
            $table->unsignedTinyInteger('after_rent_time')->default(0);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_rent');
    }
}

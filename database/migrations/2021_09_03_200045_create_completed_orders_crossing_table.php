<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class CreateCompletedOrdersCrossingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('completed_orders_crossing', function (Blueprint $table) {
            $table->increments('completed_order_crossing_id')->index('completed_orders_crossing_id');
            $table->unsignedInteger('completed_id')->index('completed_orders_crossing_completed_id');

            $table->decimal('in_price')->nullable();
            $table->decimal('out_price')->nullable();
            $table->decimal('in_distance_price')->nullable();
            $table->decimal('in_duration_price')->nullable();
            $table->decimal('out_distance_price')->nullable();
            $table->decimal('out_duration_price')->nullable();
            $table->json('in_trajectory')->nullable();
            $table->json('out_trajectory')->nullable();
            $table->unsignedFloat('in_distance',5,1)->nullable();
            $table->unsignedFloat('out_distance',5,1)->nullable();
            $table->unsignedSmallInteger('in_duration')->nullable();
            $table->unsignedSmallInteger('out_duration')->nullable();

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
        Schema::dropIfExists('completed_orders_crossing');
    }
}

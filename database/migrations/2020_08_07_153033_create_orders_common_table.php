<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrdersCommonTable
 */
class CreateOrdersCommonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('orders_common', static function (Blueprint $table) {
            $table->increments('order_common_id')->index('orders_common_id');
            $table->unsignedInteger('order_id')->index('orders_common_order_id');
            $table->json('driver');
            $table->unsignedTinyInteger('distance')->comment('Radius by kilometer (KM)')->nullable();
            $table->unsignedTinyInteger('filter_type')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('emergency')->default(false);
            $table->boolean('manual')->default(false);
            $table->boolean('accept')->default(false);
            $table->dateTime('accepted')->nullable();

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
        Schema::dropIfExists('orders_common');
    }
}

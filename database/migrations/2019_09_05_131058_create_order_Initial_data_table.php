<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderInitialDataTable
 */
class CreateOrderInitialDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_initial_data',
            static function (Blueprint $table) {
                $table->increments('order_initial_data_id')->index('index_initial_order_data_id');
                $table->unsignedInteger('order_id')->nullable()->index('index_initial_order_data_order');
                $table->unsignedInteger('initial_tariff_id')->index('index_initial_order_data_initial_tariff_id')->nullable();
                $table->unsignedInteger('second_tariff_id')->index('index_initial_order_data_second_tariff_id')->nullable();
                $table->unsignedInteger('region_id')->index('index_initial_order_data_region_id');
                $table->unsignedInteger('city_id')->nullable()->index('index_initial_order_data_city_id');
                $table->unsignedInteger('orderable_id')->index('index_initial_order_data_orderable_id');
                $table->string('orderable_type', 30)->index('index_initial_order_data_orderable_type');

                $table->unsignedTinyInteger('behind')->index('index_initial_order_data_behind')->nullable();
                $table->decimal('lat', 10, 8);
                $table->decimal('lut', 11, 8);
                $table->decimal('price')->nullable();
                $table->decimal('option_price')->nullable();
                $table->decimal('sitting_price')->nullable();
                $table->char('currency',6)->nullable();
                $table->boolean('sitting_fee')->default(0);
                $table->boolean('initial')->default(0);
                $table->boolean('waiting_cancel')->default(0);

                $table->unsignedFloat('distance', 5, 1)->nullable();
                $table->unsignedSmallInteger('duration')->nullable();

                $table->softDeletes();
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
        Schema::dropIfExists('initial_order_data');
    }
}

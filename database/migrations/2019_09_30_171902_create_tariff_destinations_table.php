<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTariffDestinationsTable
 */
class CreateTariffDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariff_destinations', static function (Blueprint $table) {
            $table->increments('tariff_destination_id')->index('index_tariff_destination_id');
            $table->unsignedInteger('tariff_id')->index('index_tariff_destination_tariff_id');
            $table->unsignedInteger('price_type_id')->index('index_tariff_destination_price_type_id');

            $table->unsignedInteger('destination_from_id')->nullable();
            $table->unsignedInteger('destination_to_id')->nullable();
            $table->decimal('price');
            $table->decimal('cancel_fee')->default(0);

            $table->boolean('sitting_fee')->default(0)->nullable();
            $table->decimal('sit_price_km')->default(0)->nullable();
            $table->decimal('sit_fix_price')->default(0)->nullable();
            $table->decimal('sit_price_minute')->default(0)->nullable();

            $table->unsignedTinyInteger('free_wait_stop_minutes')->nullable();
            $table->decimal('paid_wait_stop_minute')->nullable();
            $table->unsignedSmallInteger('change_initial_price_percent')->nullable();

            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tariff_destinations');
    }
}

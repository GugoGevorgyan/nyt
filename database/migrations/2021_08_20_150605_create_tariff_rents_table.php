<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Core\Enums\ConstTariffType;

/**
 *
 */
class CreateTariffRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tariff_rents', function (Blueprint $table) {
            $table->increments('tariff_rent_id')->index('tariff_rents_id');
            $table->unsignedInteger('tariff_id')->index('tariff_rents_tariff_id');
            $table->unsignedInteger('area_id')->index('tariff_rents_area_id');
            $table->unsignedInteger('price_type_id')
                ->default(ConstTariffType::KM_AND_MIN()->getValue())
                ->index('tariff_rents_price_type_id');

            $table->decimal('cancel_fee')->default(0);

            $table->boolean('sitting_fee')->default(0);
            $table->decimal('sit_fix_price')->default(0);
            $table->decimal('sit_price_km')->default(0);
            $table->decimal('sit_price_minute')->default(0);

            $table->float('zone_distance', 5, 1)->default(0);
            $table->unsignedTinyInteger('hours')->default(0);

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
        Schema::dropIfExists('tariff_rents');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToTariffRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tariff_rents', function (Blueprint $table) {
            $table->foreign('tariff_id', 'tariff_rent_tariff_id_foreign')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('area_id', 'tariff_rent_area_id_foreign')
                ->references('area_id')
                ->on('areas')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('price_type_id', 'tariff_rent_price_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('tariff_rent', function (Blueprint $table) {
            $table->dropForeign('tariff_rent_tariff_id_foreign');
            $table->dropForeign('tariff_rent_area_id_foreign');
            $table->dropForeign('tariff_rent_price_type_id_foreign');
        });
    }
}

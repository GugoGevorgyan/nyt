<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToTariffRentAltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tariff_rent_alt', function (Blueprint $table) {
            $table->foreign('rent_id', 'tariff_alt_rent_tariff_rent_id_foreign')
                ->references('tariff_rent_id')
                ->on('tariff_rents')
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
        Schema::table('tariff_alt', function (Blueprint $table) {
            $table->dropForeign('tariff_alt_rent_tariff_rent_id_foreign');
        });
    }
}

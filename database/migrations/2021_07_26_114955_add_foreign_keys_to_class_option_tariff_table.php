<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToClassOptionTariffTable
 */
class AddForeignKeysToClassOptionTariffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('class_option_tariff', function (Blueprint $table) {
            $table->foreign('class_id', 'class_option_tariff_foreign_class_id')
                ->references('car_class_id')
                ->on('cars_class')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('option_id', 'class_option_tariff_foreign_option_id')
                ->references('car_option_id')
                ->on('car_options')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('tariff_id', 'class_option_tariff_foreign_tariff_id')
                ->references('tariff_id')
                ->on('tariffs')
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
        Schema::table('class_option_tariff', function (Blueprint $table) {
            $table->dropForeign('class_option_tariff_foreign_class_id');
            $table->dropForeign('class_option_tariff_foreign_option_id');
            $table->dropForeign('class_option_tariff_foreign_tariff_id');
        });
    }
}

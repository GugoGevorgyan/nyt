<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToCompanyTariffTable
 */
class AddForeignKeysToCompanyTariffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_tariff', function (Blueprint $table) {
            $table->foreign('company_id', 'company_tariffs_company_tariff_id_foreign')
                ->references('company_id')
                ->on('companies')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('tariff_id', 'tariffs_tariff_id_foreign')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('company_tariffs', function (Blueprint $table) {
//            $table->dropForeign('company_tariffs_company_tariff_id_foreign');
//            $table->dropForeign('tariffs_tariff_id_foreign');
//        });
    }
}

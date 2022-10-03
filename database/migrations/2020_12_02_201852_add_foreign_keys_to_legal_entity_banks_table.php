<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToLegalEntityBanksTable
 */
class AddForeignKeysToLegalEntityBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_entity_banks', function (Blueprint $table) {
            $table->foreign('entity_id', 'legal_entity_banks_foreign_entity_id')
                ->references('legal_entity_id')
                ->on('legal_entities')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('country_id', 'legal_entity_banks_foreign_country_id')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('region_id', 'legal_entity_banks_foreign_region_id')
                ->references('region_id')
                ->on('regions')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('city_id', 'legal_entity_banks_foreign_city_id')
                ->references('city_id')
                ->on('cities')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_entity_banks', function (Blueprint $table) {
            $table->dropForeign('legal_entity_banks_foreign_entity_id');
            $table->dropForeign('legal_entity_banks_foreign_country_id');
            $table->dropForeign('legal_entity_banks_foreign_region_id');
            $table->dropForeign('legal_entity_banks_foreign_city_id');
        });
    }
}

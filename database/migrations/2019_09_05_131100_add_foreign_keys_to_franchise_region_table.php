<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToFranchiseRegionTable
 */
class AddForeignKeysToFranchiseRegionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_region', function (Blueprint $table) {
            $table
                ->foreign('franchise_id', 'franchise_region_franchise_id_foreign')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('region_id', 'franchise_region_region_id_foreign')
                ->references('region_id')
                ->on('regions')
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
        Schema::table('franchise_region', function (Blueprint $table) {
            $table->dropForeign('franchise_region_franchise_id_foreign');
            $table->dropForeign('franchise_region_region_id_foreign');
            $table->dropForeign('franchise_region_city_id_foreign');
        });
    }
}

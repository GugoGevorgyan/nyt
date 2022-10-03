<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToFranchiseCityTable
 */
class AddForeignKeysToFranchiseCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_city', static function (Blueprint $table) {
            $table
                ->foreign('franchise_id', 'franchise_city_foreign_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('franchise_region_id', 'franchise_city_foreign_franchise_region_id')
                ->references('franchise_region_id')
                ->on('franchise_region')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('city_id', 'franchise_city_foreign_city_id')
                ->references('city_id')
                ->on('cities')
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
        Schema::table('franchise_city', function (Blueprint $table) {
            $table->dropForeign('franchise_city_foreign_franchise_id');
            $table->dropForeign('franchise_city_foreign_franchise_region_id');
            $table->dropForeign('franchise_city_foreign_city_id');
        });
    }
}

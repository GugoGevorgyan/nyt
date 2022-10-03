<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToDriversTable
 */
class AddForeignKeysToDriversTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'drivers',
            function (Blueprint $table) {
                $table
                    ->foreign('driver_info_id', 'drivers_driver_info_id')
                    ->references('driver_info_id')
                    ->on('drivers_info')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('current_franchise_id', 'drivers_current_franchise_id')
                    ->references('franchise_id')
                    ->on('franchisee')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('current_status_id', 'drivers_current_status_id')
                    ->references('driver_status_id')
                    ->on('driver_statuses')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('rating_level_id', 'drivers_rating_level_id')
                    ->references('driver_rating_level_id')
                    ->on('driver_rating_levels')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('entity_id', 'drivers_foreign_entity_id')
                    ->references('legal_entity_id')
                    ->on('legal_entities')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('car_id', 'drivers_foreign_car_id')
                    ->references('car_id')
                    ->on('cars')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');
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
        Schema::table(
            'drivers',
            function (Blueprint $table) {
                $table->dropForeign('drivers_driver_info_id');
                $table->dropForeign('drivers_current_franchise_id');
                $table->dropForeign('drivers_current_status_id');
                $table->dropForeign('drivers_rating_level_id');
                $table->dropForeign('drivers_foreign_entity_id');
                $table->dropForeign('drivers_foreign_car_id');
                $table->dropForeign('drivers_foreign_type_id');
                $table->dropForeign('drivers_foreign_subtype_id');
                $table->dropForeign('drivers_foreign_graphic_id');
            }
        );
    }
}

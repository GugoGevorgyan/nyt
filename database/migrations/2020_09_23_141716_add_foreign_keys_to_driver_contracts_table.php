<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToDriverContractsTable
 */
class AddForeignKeysToDriverContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'driver_contracts',
            static function (Blueprint $table) {
                $table
                    ->foreign('driver_id', 'driver_contracts_foreign_id')
                    ->references('driver_id')
                    ->on('drivers')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table
                    ->foreign('driver_type_id', 'driver_contracts_foreign_driver_type_id')
                    ->references('driver_type_id')
                    ->on('driver_types')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table
                    ->foreign('driver_subtype_id', 'driver_contracts_foreign_driver_subtype_id')
                    ->references('driver_subtype_id')
                    ->on('driver_subtypes')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table
                    ->foreign('driver_graphic_id', 'driver_contracts_foreign_driver_graphic_id')
                    ->references('driver_graphic_id')
                    ->on('driver_graphics')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table
                    ->foreign('car_id', 'driver_contracts_foreign_car_id')
                    ->references('car_id')
                    ->on('cars')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table
                    ->foreign('entity_id', 'driver_contracts_foreign_entity_id')
                    ->references('legal_entity_id')
                    ->on('legal_entities')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'driver_contracts',
            static function (Blueprint $table) {
                $table->dropForeign('driver_contracts_foreign_id');
                $table->dropForeign('driver_contracts_foreign_driver_type_id');
                $table->dropForeign('driver_contracts_foreign_driver_subtype_id');
                $table->dropForeign('driver_contracts_foreign_driver_graphic_id');
                $table->dropForeign('driver_contracts_foreign_car_id');
                $table->dropForeign('driver_contracts_foreign_entity_id');
            }
        );
    }
}

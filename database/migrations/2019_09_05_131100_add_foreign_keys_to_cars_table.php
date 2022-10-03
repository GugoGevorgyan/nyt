<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToCarsTable
 */
class AddForeignKeysToCarsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'cars',
            function (Blueprint $table) {
//                $table
//                    ->foreign('car_class_id', 'cars_class_id')
//                    ->references('car_class_id')
//                    ->on('cars_class')
//                    ->onUpdate('NO ACTION')
//                    ->onDelete('NO ACTION');

//			$table //@todo conflict
//                ->foreign('current_driver_id', 'cars_current_driver_id')
//                ->references('driver_id')
//                ->on('drivers')
//                ->onUpdate('NO ACTION')
//                ->onDelete('NO ACTION');

                $table
                    ->foreign('park_id', 'cars_park_id')
                    ->references('park_id')
                    ->on('parks')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
                $table
                    ->foreign('entity_id', 'cars_entity_id')
                    ->references('legal_entity_id')
                    ->on('legal_entities')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
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
            'cars',
            function (Blueprint $table) {
//			$table->dropForeign('cars_current_driver_id'); //@todo conflict
                $table->dropForeign('cars_park_id');
                $table->dropForeign('cars_entity_id');
            }
        );
    }

}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToWaybillsTable
 */
class AddForeignKeysToWaybillsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'waybills',
            static function (Blueprint $table) {
                $table->foreign('car_id', 'waybills_foreign_car_id')
                    ->references('car_id')
                    ->on('cars')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table->foreign('driver_id', 'waybills_foreign_driver_id')
                    ->references('driver_id')
                    ->on('drivers')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table->foreign('system_worker_id', 'waybills_foreign_system_worker_id')
                    ->references('system_worker_id')
                    ->on('system_workers')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table->foreign('terminal_id', 'waybills_foreign_terminal_id')
                    ->references('terminal_id')
                    ->on('terminals')
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
    public function down(): void
    {
        Schema::table(
            'waybills',
            static function (Blueprint $table) {
                $table->dropForeign('waybills_car_id');
                $table->dropForeign('waybills_driver_id');
                $table->dropForeign('waybills_waybill_transaction_id');
                $table->dropForeign('waybills_system_worker_id');
                $table->dropForeign('waybills_foreign_terminal_id');
            }
        );
    }

}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToCarCrashesTable
 */
class AdForeignKeysToCarCrashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('car_crashes', function (Blueprint $table) {
            $table
                ->foreign('car_id', 'car_crashes_foreign_car_id')
                ->references('car_id')
                ->on('cars')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table
                ->foreign('driver_id', 'car_crashes_foreign_driver_id')
                ->references('driver_id')
                ->on('drivers')
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
        Schema::table('car_crashes', function (Blueprint $table) {
            $table->dropForeign('car_crashes_foreign_car_id');
            $table->dropForeign('car_crashes_foreign_driver_id');
        });
    }
}

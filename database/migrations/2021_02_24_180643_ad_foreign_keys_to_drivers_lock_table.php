<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToDriversLockTable
 */
class AdForeignKeysToDriversLockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers_lock', function (Blueprint $table) {
            $table
                ->foreign('driver_id', 'drivers_lock_foreign_driver_id')
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
        Schema::table('drivers_lock', function (Blueprint $table) {
            $table->dropForeign('drivers_lock_foreign_driver_id');
        });
    }
}

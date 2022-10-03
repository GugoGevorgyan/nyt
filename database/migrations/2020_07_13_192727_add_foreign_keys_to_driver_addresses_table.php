<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToDriverAddressesTable
 */
class AddForeignKeysToDriverAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'driver_addresses',
            static function (Blueprint $table) {
                $table->foreign('driver_id', 'driver_addresses_foreign_driver_id')
                    ->references('driver_id')
                    ->on('drivers')
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
            'driver_addresses',
            static function (Blueprint $table) {
                $table->dropForeign('driver_addresses_foreign_driver_id');
            }
        );
    }
}

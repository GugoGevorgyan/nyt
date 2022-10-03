<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToDriverInfoLicenseTypeTable
 */
class AddForeignKeysToDriverInfoLicenseTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_info_license_type', function (Blueprint $table) {
            $table
                ->foreign('driver_info_id', 'driver_info_license_type_foreign_driver_info_id')
                ->references('driver_info_id')
                ->on('drivers_info')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('license_type_id', 'driver_info_license_type_foreign_license_type_id')
                ->references('driver_license_type_id')
                ->on('driver_license_types')
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
        Schema::table('driver_license_type', function (Blueprint $table) {
            $table->dropForeign('driver_info_license_type_foreign_driver_info_id');
            $table->dropForeign('driver_info_license_type_foreign_license_type_id');
        });
    }
}

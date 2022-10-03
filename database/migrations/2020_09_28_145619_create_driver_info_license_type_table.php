<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverInfoLicenseTypeTable
 */
class CreateDriverInfoLicenseTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_info_license_type', function (Blueprint $table) {
            $table->increments('driver_info_license_type_id')->index('index_driver_info_license_type_id');
            $table->unsignedInteger('driver_info_id');
            $table->unsignedInteger('license_type_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_license');
    }
}

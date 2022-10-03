<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateClientSettingsTable
 */
class CreateClientSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_settings', static function (Blueprint $table) {
            $table->increments('client_setting_id')->index('client_settings_setting_id');
            $table->unsignedInteger('client_id')->index('client_settings_client_id');
            $table->boolean('show_driver_my_coordinates')->default(0);
            $table->boolean('not_call')->default(0);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_settings');
    }
}

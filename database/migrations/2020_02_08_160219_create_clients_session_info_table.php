<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateClientsSessionInfoTable
 */
class CreateClientsSessionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'clients_session_info',
            static function (Blueprint $table) {
                $table->increments('client_session_info_id')->index('index_client_session_info_id');
                $table->unsignedInteger('clientable_id')->index('clients_session_info_client_id');
                $table->string('clientable_type', 120);
                $table->unsignedInteger('country_id')->nullable()->index('clients_session_info_country_id');
                $table->unsignedInteger('region_id')->nullable()->index('clients_session_info_region_id');
                $table->unsignedInteger('city_id')->nullable()->index('clients_session_info_city_id');
                $table->ipAddress('ip_address')->nullable();
                $table->string('device')->nullable();
                $table->string('platform')->nullable();
                $table->boolean('mobile')->default(0);
                $table->timestamps(6);
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
        Schema::dropIfExists('clients_session_info');
    }
}

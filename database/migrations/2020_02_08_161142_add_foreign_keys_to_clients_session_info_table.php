<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToClientsSessionInfoTable
 */
class AddForeignKeysToClientsSessionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients_session_info', static function (Blueprint $table) {
            $table->foreign('country_id', 'clients_session_info_country_id')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('region_id', 'clients_session_info_region_id')
                ->references('region_id')
                ->on('regions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('city_id', 'clients_session_info_city_id')
                ->references('city_id')
                ->on('cities')
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
        Schema::table('clients_session_info', function (Blueprint $table) {
            $table->dropForeign('clients_session_info_country_id');
            $table->dropForeign('clients_session_info_region_id');
            $table->dropForeign('clients_session_info_city_id');
        });
    }
}

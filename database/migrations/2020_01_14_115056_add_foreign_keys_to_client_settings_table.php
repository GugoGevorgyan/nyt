<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToClientSettingsTable
 */
class AddForeignKeysToClientSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_settings', static function (Blueprint $table) {
            $table->foreign('client_id', 'client_settings_client_id')
                ->references('client_id')
                ->on('clients')
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
        Schema::table('client_settings', static function (Blueprint $table) {
            $table->dropForeign('client_settings_client_id');
        });
    }
}

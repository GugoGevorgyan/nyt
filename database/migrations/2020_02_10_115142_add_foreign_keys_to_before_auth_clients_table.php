<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToBeforeAuthClientsTable
 */
class AddForeignKeysToBeforeAuthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('before_auth_clients', static function (Blueprint $table) {
            $table->foreign('client_id', 'before_auth_clients_client_id')
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
        Schema::table('before_auth_clients', static function (Blueprint $table) {
            $table->dropForeign('before_auth_clients_client_id');
        });
    }
}

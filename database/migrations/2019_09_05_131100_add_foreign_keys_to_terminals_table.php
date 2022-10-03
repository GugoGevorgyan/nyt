<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToTerminalsTable
 */
class AddForeignKeysToTerminalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'terminals',
            function (Blueprint $table) {
                $table->foreign('park_id', 'terminals_park_id')->references('park_id')->on('parks')->onUpdate('NO ACTION')->onDelete('CASCADE');
                $table->foreign('auth_driver_id', 'terminals_auth_driver_id')->references('driver_id')->on('drivers')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
        Schema::table(
            'terminals',
            function (Blueprint $table) {
                $table->dropForeign('terminals_park_id');
                $table->dropForeign('terminals_auth_driver_id');
            }
        );
    }

}

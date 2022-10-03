<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToAirportsTable
 */
class AddForeignKeysToAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'airports',
            static function (Blueprint $table) {
                $table->foreign('city_id', 'airports_foreign_city_id')
                    ->references('city_id')
                    ->on('cities')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');
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
            'airports',
            static function (Blueprint $table) {
                $table->dropForeign('airports_foreign_city_id');
            }
        );
    }
}

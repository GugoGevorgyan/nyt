<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToMetrosTable
 */
class AddForeignKeysToMetrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'metros',
            static function (Blueprint $table) {
                $table->foreign('city_id', 'metros_foreign_city_id')
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
            'metros',
            static function (Blueprint $table) {
                $table->dropForeign('metros_foreign_city_id');
            }
        );
    }
}

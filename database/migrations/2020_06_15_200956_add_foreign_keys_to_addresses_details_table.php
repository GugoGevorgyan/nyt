<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToAddressesDetailsTable
 */
class AddForeignKeysToAddressesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'addresses_details',
            static function (Blueprint $table) {
                $table->foreign('initial_address_id', 'addresses_details_initial_address_foreign_id')
                    ->references('address_id')
                    ->on('addresses')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');

                $table->foreign('end_address_id', 'addresses_details_end_address_foreign_id')
                    ->references('address_id')
                    ->on('addresses')
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
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
            'addresses_details',
            static function (Blueprint $table) {
                $table->dropForeign('addresses_details_initial_address_foreign_id');
                $table->dropForeign('addresses_details_initial_address_end_id');
            }
        );
    }
}

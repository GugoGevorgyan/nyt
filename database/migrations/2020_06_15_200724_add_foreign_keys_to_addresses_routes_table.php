<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToAddressesRoutesTable
 */
class AddForeignKeysToAddressesRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'addresses_routes',
            static function (Blueprint $table) {
                $table->foreign('detail_id', 'addresses_routes_detail_foreign_id')
                    ->references('address_detail_id')
                    ->on('addresses_details')
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
    public function down(): void
    {
        Schema::table(
            'addresses_routes',
            static function (Blueprint $table) {
                $table->dropForeign('addresses_routes_detail_foreign_id');
            }
        );
    }
}

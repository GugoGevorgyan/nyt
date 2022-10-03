<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToDriverTypeOptionalOptionTable
 */
class AdForeignKeysToDriverTypeOptionalOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('driver_type_option', function (Blueprint $table) {
            $table
                ->foreign('franchise_id', 'driver_type_optional_option_foreign_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table
                ->foreign('driver_type_id', 'driver_type_optional_option_foreign_driver_type_id')
                ->references('driver_type_id')
                ->on('driver_types')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table
                ->foreign('driver_type_optional_id', 'driver_type_optional_option_foreign_driver_type_optional_id')
                ->references('driver_type_optional_id')
                ->on('driver_type_optionals')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('driver_type_option', function (Blueprint $table) {
            $table->dropForeign('driver_type_optional_option_foreign_franchise_id');
            $table->dropForeign('driver_type_optional_option_foreign_driver_type_id');
            $table->dropForeign('driver_type_optional_option_foreign_driver_type_optional_id');
        });
    }
}

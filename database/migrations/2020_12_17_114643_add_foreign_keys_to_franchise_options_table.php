<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToFranchiseOptionsTable
 */
class AddForeignKeysToFranchiseOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'franchise_options',
            static function (Blueprint $table) {
                $table
                    ->foreign('franchise_id', 'franchise_options_foreign_franchise_id')
                    ->references('franchise_id')
                    ->on('franchisee')
                    ->onUpdate('NO ACTION')
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
            'franchise_options',
            static function (Blueprint $table) {
                $table->dropForeign('franchise_options_foreign_franchise_id');
            }
        );
    }
}

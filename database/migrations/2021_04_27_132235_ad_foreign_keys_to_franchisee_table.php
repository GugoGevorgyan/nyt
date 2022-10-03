<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToFranchiseeTable
 */
class AdForeignKeysToFranchiseeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('franchisee', static function (Blueprint $table) {
            $table
                ->foreign('country_id', 'franchise_foreign_country_id')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table
                ->foreign('entity_id', 'franchise_foreign_entity_id')
                ->references('legal_entity_id')
                ->on('legal_entities')
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
        Schema::table('franchisee', static function (Blueprint $table) {
            $table->dropForeign('franchise_foreign_country_id');
            $table->dropForeign('franchise_foreign_entity_id');
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToFranchiseEntitiesTable
 */
class AddForeignKeysToFranchiseEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'franchise_entities',
            function (Blueprint $table) {
                $table
                    ->foreign('franchise_id', 'franchise_entities_foreign_franchise_id')
                    ->references('franchise_id')
                    ->on('franchisee')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('legal_entity_id', 'franchise_entities_foreign_entity_id')
                    ->references('legal_entity_id')
                    ->on('legal_entities')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');
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
            'franchise_entities',
            function (Blueprint $table) {
                $table->dropForeign('franchise_entities_foreign_franchise_id');
                $table->dropForeign('franchise_entities_foreign_entity_id');
            }
        );
    }
}

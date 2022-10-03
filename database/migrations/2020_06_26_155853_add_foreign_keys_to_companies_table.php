<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCompaniesTable
 */
class AddForeignKeysToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('companies', static function (Blueprint $table) {
            $table->foreign('franchise_id', 'companies_foreign_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('entity_id', 'companies_foreign_entity_id')
                ->references('legal_entity_id')
                ->on('legal_entities')
                ->onUpdate('CASCADE')
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
        Schema::table('companies', static function (Blueprint $table) {
            $table->dropForeign('companies_foreign_franchise_id');
            $table->dropForeign('companies_foreign_entity_id');
        });
    }
}

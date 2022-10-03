<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCompanyReportsTable
 */
class AddForeignKeysToCompanyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'company_reports',
            function (Blueprint $table) {
                $table
                    ->foreign('company_id', 'company_reports_foreign_company_id')
                    ->references('company_id')
                    ->on('companies')
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
            'company_reports',
            function (Blueprint $table) {
                $table->dropForeign('company_reports_foreign_company_id');
            }
        );
    }
}

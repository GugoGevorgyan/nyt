<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCarReportsTable
 */
class AddForeignKeysToCarReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'car_reports',
            function (Blueprint $table) {
                $table
                    ->foreign('waybill_id', 'car_reports_waybill_id')
                    ->references('waybill_id')
                    ->on('waybills')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table
                    ->foreign('question_id', 'car_reports_question_id')
                    ->references('question_id')
                    ->on('car_report_questions')
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
            'car_reports',
            function (Blueprint $table) {
                $table->dropForeign('car_reports_waybill_id');
                $table->dropForeign('car_reports_question_id');
            }
        );
    }
}

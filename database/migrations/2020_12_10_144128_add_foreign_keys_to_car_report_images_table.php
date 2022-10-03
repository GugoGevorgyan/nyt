<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCarReportImagesTable
 */
class AddForeignKeysToCarReportImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('car_report_images', function (Blueprint $table) {
            $table->foreign('report_id', 'car_report_images_foreign_report_id')
                ->references('car_report_id')
                ->on('car_reports')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('car_report_images', static function (Blueprint $table) {
            $table->dropForeign('car_report_images_foreign_report_id');
        });
    }
}

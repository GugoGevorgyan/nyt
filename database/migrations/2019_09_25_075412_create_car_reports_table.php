<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCarReportsTable
 */
class CreateCarReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('car_reports', function (Blueprint $table) {
            $table->increments('car_report_id')->index('index_car_report_id');
            $table->unsignedInteger('waybill_id');
            $table->unsignedInteger('question_id');

            $table->boolean('verified')->default(0);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_reports');
    }
}

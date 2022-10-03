<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCarReportQuestionsTable
 */
class CreateCarReportQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('car_report_questions', function (Blueprint $table) {
            $table->increments('question_id')->index('index_question_id');
            $table->text('question');
            $table->string('field_name');
            $table->unsignedTinyInteger('point')->default(0);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('car_report_questions');
    }
}

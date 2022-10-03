<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCarReportImagesTable
 */
class CreateCarReportImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_report_images', function (Blueprint $table) {
            $table->increments('car_report_image_id');
            $table->unsignedInteger('report_id');
            $table->char('path',250);
            $table->char('name',64)->unique();
            $table->char('ext',5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_report_images');
    }
}

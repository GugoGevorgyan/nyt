<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCarsTable
 */
class CreateCarsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('car_id')->index('index_car_id');
            $table->unsignedInteger('park_id')->nullable()->index('cars_park_id');
            $table->unsignedInteger('current_driver_id')->nullable()->index('cars_current_driver_id');
            $table->unsignedInteger('franchise_id')->nullable()->index('cars_franchise_id');
            $table->unsignedInteger('status_id')->index('cars_status_id');
            $table->unsignedInteger('entity_id')->nullable()->index('cars_entity_id');

            $table->json('option')->nullable();
            $table->json('class');

            $table->double('rating', 3, 1)->default(0);
            $table->string('vin_code');
            $table->string('body_number')->nullable();
            $table->string('vehicle_licence_number');
            $table->date('vehicle_licence_date')->nullable();
            $table->date('registration_date');
            $table->string('sts_number', 32)->nullable();
            $table->string('pts_number', 32)->nullable();
            $table->string('sts_file', 300)->nullable();
            $table->string('pts_file', 300)->nullable();
            $table->string('mark', 150)->nullable();
            $table->string('model', 200)->nullable();
            $table->year('year')->nullable();
            $table->json('images')->nullable();
            $table->date('inspection_date')->nullable();
            $table->date('inspection_expiration_date')->nullable();
            $table->string('inspection_scan')->nullable();
            $table->date('insurance_date')->nullable();
            $table->date('insurance_expiration_date')->nullable();
            $table->string('insurance_scan')->nullable();
            $table->string('color', 24)->nullable();
            $table->char('state_license_plate', 9)->nullable();
            $table->integer('speedometer')->nullable();
            $table->integer('garage_number')->nullable();
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
        Schema::drop('cars');
    }

}

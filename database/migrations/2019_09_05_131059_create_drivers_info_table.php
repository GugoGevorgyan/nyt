<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDriversInfoTable
 */
class CreateDriversInfoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'drivers_info',
            function (Blueprint $table) {
                $table->increments('driver_info_id')->index('index_driver_info_id');
                $table->integer('franchise_id')->unsigned()->nullable()->index('driver_info_franchise_id');
                $table->date('birthday');
                $table->string('name')->index('drivers_info_name');
                $table->string('surname')->index('drivers_info_surname');
                $table->string('patronymic')->index('drivers_info_patronymic');
                $table->string('email', 150)->nullable()->unique('drivers_driver_email_uindex');
                $table->string('citizen');
                $table->string('address');
                $table->string('id_kis_art', 50)->nullable();

                $table->string('photo', 100)->nullable();
                $table->string('license_qr_code', 250)->nullable();
                $table->string('license_scan')->nullable();
                $table->string('passport_scan')->nullable();

                $table->bigInteger('license_code');
                $table->date('license_date');
                $table->date('license_expiry');
                $table->string('passport_serial', 35);
                $table->string('passport_number', 35);
                $table->string('passport_issued_by');
                $table->date('passport_when_issued');
                $table->tinyInteger('experience')->nullable();
                $table->decimal('deposit')->default(0.0);
                $table->timestamps(6);
            }
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('drivers_info');
    }

}

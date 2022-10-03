<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalEntityBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_entity_banks', function (Blueprint $table) {
            $table->increments('entity_bank_id')->index('index_entity_bank_id');
            $table->integer('entity_id')->unsigned()->index('bank_entity_id');
            $table->integer('country_id')->unsigned()->index('bank_country_id');
            $table->integer('region_id')->unsigned()->index('bank_region_id');
            $table->integer('city_id')->unsigned()->index('bank_city_id');
            $table->string('name');
            $table->string('bank_account_number')->comment('Р/сч');
            $table->string('correspondent_account_number')->comment('К/сч');
            $table->string('bank_identification_account')->comment('БИК');
            $table->softDeletes();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legal_entity_banks');
    }
}

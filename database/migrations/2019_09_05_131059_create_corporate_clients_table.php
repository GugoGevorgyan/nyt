<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCorporateClientsTable
 */
class CreateCorporateClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporate_clients', function (Blueprint $table) {
            $table->increments('corporate_client_id')->index('index_corporate_client_id');
            $table->integer('client_id')->unsigned()->nullable()->index('corporate_client_client_id');
            $table->integer('company_id')->unsigned()->nullable()->index('corporate_client_company_id');
            $table->json('car_classes_ids')->nullable();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('patronymic')->nullable();
            $table->boolean('allow_weekends')->nullable()->default(0);
            $table->boolean('allow_order')->nullable()->default(0);
            $table->integer('limit')->default(4000);
            $table->float('spent', 7)->default(0.00);
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
        Schema::drop('corporate_clients');
    }

}

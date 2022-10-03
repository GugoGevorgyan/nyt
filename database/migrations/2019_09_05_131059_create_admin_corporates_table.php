<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateAdminCorporatesTable
 */
class CreateAdminCorporatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_corporates', function (Blueprint $table) {
            $table->increments('admin_corporate_id')->index('index_admin_corporate_id');
            $table->integer('company_id')->unsigned()->index('admin_corporate_company_id');
            $table->integer('franchise_id')->unsigned()->index('admin_corporate_franchise_id');
            $table->string('name', 100)->nullable();
            $table->string('surname', 100)->nullable();
            $table->string('patronymic', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('email', 150)->unique('personal_company_users_email_uindex');
            $table->string('remember_token', 150)->nullable();
            $table->string('password');
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
        Schema::drop('admin_corporates');
    }

}

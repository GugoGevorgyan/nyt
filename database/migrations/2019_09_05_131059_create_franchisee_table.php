<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateFranchiseeTable
 */
class CreateFranchiseeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchisee', function (Blueprint $table) {
            $table->increments('franchise_id')->index('index_franchise_id');
            $table->integer('entity_id')->unsigned()->index('franchise_entity_id');
            $table->integer('country_id')->unsigned()->index('franchise_country_id');
            $table->string('logo')->nullable();
            $table->string('name', 150);
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('text')->nullable();
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
        Schema::drop('franchisee');
    }

}

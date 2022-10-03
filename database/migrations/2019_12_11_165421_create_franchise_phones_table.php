<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchisePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_phones', function (Blueprint $table) {
            $table->increments('franchise_phone_id')->index('index_franchise_phone_id');
            $table->integer('franchise_id')->unsigned()->nullable()->index('franchises_id');
            $table->string('number');
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
        Schema::dropIfExists('franchise_phones');
    }
}

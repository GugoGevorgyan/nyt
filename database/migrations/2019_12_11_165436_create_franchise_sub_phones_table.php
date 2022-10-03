<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFranchiseSubPhonesTable
 */
class CreateFranchiseSubPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_sub_phones', function (Blueprint $table) {
            $table->increments('franchise_sub_phone_id')->index('index_franchise_sub_phone_id');
            $table->unsignedInteger('franchise_phone_id')->nullable()->index('franchise_phones_id');
            $table->string('number');
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
        Schema::dropIfExists('franchise_sub_phones');
    }
}

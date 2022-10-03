<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateClientAddressesTable
 */
class CreateClientAddressesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->increments('client_address_id')->index('index_client_address_id');
            $table->unsignedInteger('client_id')->index('client_addresses_client_id');
            $table->string('name')->nullable();
            $table->string('short_address')->nullable();
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 8);
            $table->decimal('lut', 11, 8);
            $table->boolean('favorite')->nullable()->default(0);
            $table->text('driver_hint')->nullable();
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
        Schema::drop('client_addresses');
    }

}

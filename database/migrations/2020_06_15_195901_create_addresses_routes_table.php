<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAddressesRoutesTable
 */
class CreateAddressesRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_routes', static function (Blueprint $table) {
            $table->increments('address_route_id')->index('addresses_routes_index_id');
            $table->unsignedInteger('detail_id')->index('addresses_routes_detail_index_id');
            $table->json('route');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses_routes');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAddressesDetailsTable
 */
class CreateAddressesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'addresses_details',
            static function (Blueprint $table) {
                $table->increments('address_detail_id')->index('addresses_details_address_detail_index_id');
                $table->unsignedInteger('initial_address_id')->index('addresses_details_initial_address_index_id');
                $table->unsignedInteger('end_address_id')->index('addresses_details_end_address_index_id');
                $table->unsignedFloat('distance',5,1)->comment('distance by kilometers');
                $table->unsignedSmallInteger('duration')->comment('duration by minutes');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses_details');
    }
}

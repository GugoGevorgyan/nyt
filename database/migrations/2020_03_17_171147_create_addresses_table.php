<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAddressesTable
 */
class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'addresses',
            static function (Blueprint $table) {
                $table->increments('address_id')->index('addresses_address_index_id');
                $table->string('address', 300)->index('addresses_index_address')->nullable();
                $table->string('short_address', 100)->index('addresses_index_short_address')->nullable();
                $table->string('province', 70)->index('addresses_index_province')->nullable();
                $table->string('locality', 50)->index('addresses_index_locality')->nullable();
                $table->char('code', 4);
                $table->decimal('lat', 10, 8)->index('addresses_index_lat');
                $table->decimal('lut', 11, 8)->index('addresses_index_lut');

                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('addresses');
    }
}

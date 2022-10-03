<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Core\Enums\ConstDriverAddressType;

/**
 * Class CreateDriverAddressesTable
 */
class CreateDriverAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'driver_addresses',
            static function (Blueprint $table) {
                $table->increments('driver_address_id')->index('driver_address_index_id');
                $table->unsignedInteger('driver_id')->index('driver_address_index_driver_id');
                $table->string('address', 250);
                $table->decimal('lat',10,8);
                $table->decimal('lut',11,8);
                $table->boolean('active')->default(false);
                $table->enum(
                    'target',
                    [ConstDriverAddressType::HOME()->getValue(), ConstDriverAddressType::WORK()->getValue()]
                )->default('HOME');
                $table->unsignedSmallInteger('target_duration')->comment('MINUTES')->nullable();
                $table->unsignedMediumInteger('target_distance')->comment('METERS')->nullable();
                $table->json('target_road')->nullable();
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
        Schema::dropIfExists('driver_addresses');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class DriverTypeOption
 */
class DriverTypeOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('driver_type_option', function (Blueprint $table) {
            $table->increments('driver_type_option_id')->index('index_driver_type_optional_option_id');
            $table->unsignedInteger('franchise_id')->index('index_driver_type_optional_franchise_id');
            $table->unsignedInteger('driver_type_id')->index('index_driver_type_optional_driver_type_id');
            $table->unsignedInteger('driver_type_optional_id')->index('index_driver_type_optional_driver_type_optional_id');

            $table->unsignedTinyInteger('percent_value')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_type_optional_option');
    }
}

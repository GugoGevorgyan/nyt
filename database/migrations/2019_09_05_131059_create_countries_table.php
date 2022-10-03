<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCountriesTable
 */
class CreateCountriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'countries',
            static function (Blueprint $table) {
                $table->increments('country_id')->index('index_country_id');
                $table->string('name')->index('index_country_name')->unique();
                $table->string('iso_2', 10)->index('index_iso_2');
                $table->string('phone_code', 25)->nullable();
                $table->string('currency', 10)->nullable();
                $table->char('phone_mask', 18)->nullable();
                $table->timestamps();
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
        Schema::drop('countries');
    }

}

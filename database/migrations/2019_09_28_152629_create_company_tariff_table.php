<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCompanyTariffTable
 */
class CreateCompanyTariffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('company_tariff', static function (Blueprint $table) {
            $table->increments('company_tariff_id')->index('index_company_tariff_id');
            $table->unsignedInteger('company_id')->index('company_tariff_foreign_company_id');
            $table->unsignedInteger('tariff_id')->index('company_tariff_foreign_tariff_id_id');

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
        Schema::dropIfExists('company_tariffs');
    }
}

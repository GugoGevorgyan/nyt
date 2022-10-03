<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateClassOptionTariffTable
 */
class CreateClassOptionTariffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('class_option_tariff', function (Blueprint $table) {
            $table->increments('class_option_tariff_id')->index('class_option_tariff_id');
            $table->unsignedInteger('tariff_id')->index('class_option_tariff_tariff_id');
            $table->unsignedInteger('class_id')->index('class_option_tariff_class_id');
            $table->unsignedInteger('option_id')->index('class_option_tariff_option_id');
            $table->decimal('price', 6)->default(0);
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
        Schema::dropIfExists('class_option_tariff');
    }
}

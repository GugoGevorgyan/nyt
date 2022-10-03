<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Src\Models\Order\PaymentType;


/**
 * Class CreateTariffsTable
 */
class CreateTariffsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'tariffs',
            static function (Blueprint $table) {
                $table->increments('tariff_id')->index('index_tariffs_tariff_id');
                $table->unsignedInteger('tariff_type_id')->index('index_tariffs_tariff_type_id');
                $table->unsignedInteger('car_class_id')->index('index_tariffs_car_class_id');
                $table->unsignedInteger('payment_type_id')
                    ->default(PaymentType::CASH)
                    ->index('index_tariffs_payment_type_id');

                $table->unsignedInteger('country_id')->index('index_tariffs_country_id');
                $table->json('region');
                $table->json('city');

                $table->unsignedInteger('tariffable_id')->index('index_tariffs_tariffable_id');
                $table->string('tariffable_type', 30)->index('index_tariffs_tariffable_type');

                $table->string('name', 200)->unique('tariffs_name_uindex');

                $table->tinyInteger('rounding_price')->default(1);
                $table->decimal('minimal_price')->default(0)->nullable();

                $table->unsignedTinyInteger('free_wait_minutes')->nullable()->comment('initial wait minutes');
                $table->decimal('paid_wait_minute')->nullable()->comment('initial wait price every minute');

                $table->boolean('tool_roads_client')->default(0);
                $table->boolean('paid_parking_client')->default(0);
                $table->decimal('diff_percent', 3, 1)->nullable();

                $table->decimal('limit_manually_cost')->nullable();

                $table->boolean('status')->default(1);
                $table->boolean('is_default')->default(0);

                $table->date('date_from')->nullable();
                $table->date('date_to')->nullable();

                $table->timestamps();
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
        Schema::drop('tariffs');
    }

}

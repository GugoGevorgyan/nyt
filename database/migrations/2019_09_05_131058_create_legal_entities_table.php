<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateLegalEntitiesTable
 */
class CreateLegalEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'legal_entities',
            function (Blueprint $table) {
                $table->increments('legal_entity_id')->index('index_entity_id');
                $table->integer('type_id')->nullable()->unsigned()->index('entity_type_id');
                $table->integer('country_id')->nullable()->unsigned()->index('entity_country_id');
                $table->integer('region_id')->nullable()->unsigned()->index('entity_region_id');
                $table->integer('city_id')->nullable()->unsigned()->index('entity_city_id');
                $table->string('name');
                $table->string('zip_code')->nullable();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('tax_inn')->nullable();
                $table->string('tax_kpp')->nullable();
                $table->string('tax_psrn')->nullable()->comment('ОГРН');
                $table->string('tax_psrn_serial')->nullable()->comment('серия ОГРН');
                $table->string('tax_psrn_issued_by')->nullable()->comment('кем выдан ОГРН');
                $table->date('tax_psrn_date')->nullable()->comment('дата выдачи ОГРН');
                $table->string('aucneb')->nullable()->comment('ОКОНХ');
                $table->string('arceo')->nullable()->comment('ОКПО');
                $table->string('arcfo')->nullable()->comment('ОКФС');
                $table->string('arclf')->nullable()->comment('ОКОПФ');
                $table->string('registration_certificate_number')
                    ->nullable()
                    ->comment('Номер свидетельства о регистрации');
                $table->date('registration_certificate_date')
                    ->nullable()
                    ->comment('Дата выдачи свидетельства о регистрации');
                $table->string('director_name')->nullable();
                $table->string('director_surname')->nullable();
                $table->string('director_patronymic')->nullable();
                $table->timestamps(6);
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
        Schema::dropIfExists('legal_entities');
    }
}

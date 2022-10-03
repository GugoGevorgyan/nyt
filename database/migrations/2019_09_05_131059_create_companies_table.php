<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCompaniesTable
 */
class CreateCompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', static function (Blueprint $table) {
            $table->increments('company_id')->index('index_companies_companies_franchise_id');
            $table->integer('franchise_id')->unsigned()->index('index_companies_franchise_id');
            $table->integer('entity_id')->unsigned()->index('index_company_entity_id');
            $table->string('name')->index('companies_name');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->integer('order_canceled_timeout')->nullable();
            $table->integer('period')->nullable()->comment('Период времени');
            $table->integer('frequency')->nullable()->comment('Количество отчетов в этот период времени');
            $table->char('code')->unique()->comment('Внутренний код компании');
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
            $table->string('contract_scan')->nullable();
            $table->string('limit')->nullable()->default('50000');
            $table->float('spent', 7)->nullable()->default(0.00);
            $table->text('details')->nullable();
            $table->timestamps(6);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('companies');
    }

}

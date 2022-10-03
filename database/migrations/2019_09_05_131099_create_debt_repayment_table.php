<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDebtRepaymentTable
 */
class CreateDebtRepaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_repayment', function (Blueprint $table) {
            $table->increments('debt_repayment_id')->index('debt_repayment_id');
            $table->unsignedTinyInteger('amount');
            $table->decimal('min_debt');
            $table->decimal('max_debt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debt_repayment');
    }
}

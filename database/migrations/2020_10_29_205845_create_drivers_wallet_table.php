<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriversWalletTable
 */
class CreateDriversWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('drivers_wallet', function (Blueprint $table) {
            $table->increments('driver_wallet_id')->index('drivers_wallet_id');
            $table->unsignedInteger('driver_id')->index('drivers_wallet_driver_id');
            $table->unsignedInteger('repayment_id')->nullable()->index('drivers_wallet_repayment_id');
            $table->unsignedTinyInteger('cash_type')->nullable();

            $table->decimal('transaction_cash')->default(0.0)->nullable();
            $table->decimal('balance')->default(0.0)->nullable();
            $table->decimal('amount_paid')->default(0.0)->nullable();
            $table->decimal('min_repayment')->default(0.0)->nullable();
            $table->decimal('min_repayment_waybill')->default(0.0)->nullable();
            $table->decimal('debt')->default(0.0)->nullable();
            $table->integer('maturity')->nullable();
            $table->boolean('is_paid')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers_cash');
    }
}

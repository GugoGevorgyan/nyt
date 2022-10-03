<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFranchiseTransactionsTable
 */
class CreateFranchiseTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('franchise_transactions', function (Blueprint $table) {
            $table->increments('franchise_transaction_id')->index('franchise_transactions_id');
            $table->char('number', 10)->index('franchise_transactions_number')->nullable();

            $table->unsignedInteger('franchise_id')->index('franchise_transactions_franchise_id');
            $table->unsignedInteger('park_id')->nullable()->index('franchise_transactions_park_id');
            $table->unsignedInteger('worker_id')->nullable()->index('franchise_transactions_worker_id');
            $table->unsignedInteger('payment_type_id')->nullable()->index('franchise_transactions_payment_type_id');
            $table->unsignedInteger('side_id')->index('franchise_transactions_side_id');
            $table->char('side_type', 30)->index('franchise_transactions_side_type');
            $table->unsignedInteger('second_side_id')->nullable()->index('franchise_transactions_second_side_type_id');
            $table->char('second_side_type', 30)->nullable()->index('franchise_transactions_second_side_type');
            $table->unsignedInteger('reason_id')->nullable()->index('franchise_transactions_reason_id');
            $table->char('reason_type', 30)->nullable()->index('franchise_transactions_reason_type');
            $table->unsignedTinyInteger('type')->index('franchise_transactions_type');

            $table->decimal('franchise_cost')->default(0);
            $table->decimal('side_cost')->default(0);
            $table->decimal('amount')->default(0);
            $table->decimal('remainder')->default(0);

            $table->boolean('out');
            $table->boolean('payed')->default(false);
            $table->char('comment', 35)->nullable();

            $table->timestamp('created_at', 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_transactions');
    }
}

<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDebtsTable
 */
class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->increments('debt_id')->index('driver_debts_id');
            $table->unsignedInteger('debtor_id')->index('debts_debtor_id');
            $table->string('debtor_type', 32)->index('debts_debtor_type');
            $table->unsignedInteger('type')->index('driver_debts_type');
            $table->decimal('cost', 10);
            $table->decimal('cost_paid', 10);
            $table->boolean('firm_paid')->default(0);
            $table->boolean('closest')->default(false);

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
        Schema::dropIfExists('debts');
    }
}

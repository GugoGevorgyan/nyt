<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateFranchiseRoleTable
 */
class CreateFranchiseRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('franchise_role', function (Blueprint $table) {
            $table->increments('franchise_role_id')->index('franchise_role_id');
            $table->unsignedInteger('franchise_module_id')->index('franchise_role_module_id');
            $table->unsignedInteger('franchise_id')->index('franchise_role_franchise_id');
            $table->unsignedInteger('role_id')->index('franchise_role_role_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_role');
    }
}

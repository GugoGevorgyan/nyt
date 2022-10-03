<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateFranchiseeModuleTable
 */
class CreateFranchiseeModuleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'franchise_module',
            function (Blueprint $table) {
                $table->increments('franchise_module_id')->index('index_franchise_module_id');
                $table->unsignedInteger('franchise_id')->index('franchise_module_franchise_id');
                $table->unsignedInteger('module_id')->index('franchise_modules_module_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::drop('franchise_module');
    }

}

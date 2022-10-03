<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateModulesTable
 */
class CreateModulesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('module_id')->index('index_module_id');
            $table->integer('route_id')->unsigned()->nullable()->index('modules_route_id');
            $table->string('text');
            $table->string('name', 200)->unique();
            $table->string('alias', 250);
            $table->string('description', 500)->nullable();
            $table->boolean('default')->default(0);
            $table->string('icon', 30)->nullable();
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
        Schema::drop('modules');
    }

}

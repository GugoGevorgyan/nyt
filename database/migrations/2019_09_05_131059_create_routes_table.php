<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRoutesTable
 */
class CreateRoutesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('route_id')->index('index_route_id');
            $table->string('namespace', 150)->nullable();
            $table->string('name', 64)->nullable()->unique('routes_name_uindex');
            $table->string('type', 10)->nullable();
            $table->string('url', 200)->nullable();
            $table->string('alias', 30)->nullable();
            $table->string('as', 30)->nullable()->unique('routes_as_uindex');
            $table->json('middleware')->nullable();
            $table->string('prefix', 200)->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('routes');
    }

}

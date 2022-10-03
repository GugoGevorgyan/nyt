<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRolesTable
 */
class CreateRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('role_id')->index('index_role_id');
            $table->integer('module_id')->unsigned()->nullable()->index('roles_module_id');
            $table->integer('homepage_route_id')->unsigned()->nullable()->index('roles_id');
            $table->string('text');
            $table->string('name')->unique();
            $table->string('alias', 150)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('guard_name');
            $table->softDeletes();
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
        Schema::drop('roles');
    }

}

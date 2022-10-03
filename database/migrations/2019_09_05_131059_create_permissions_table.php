<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreatePermissionsTable
 */
class CreatePermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('permission_id')->index('index_permission_id');
            $table->integer('route_id')->unsigned()->nullable()->index('permission_routes_id');
            $table->integer('role_id')->unsigned()->nullable()->index('permissions_role_id');
            $table->integer('homepage_route_id')->unsigned()->nullable()->index('permissions_route_id');
            $table->string('text');
            $table->string('name');
            $table->string('alias', 150)->nullable();
            $table->string('description', 999)->nullable();
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
        Schema::drop('permissions');
    }

}

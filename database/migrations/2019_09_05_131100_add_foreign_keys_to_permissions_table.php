<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToPermissionsTable
 */
class AddForeignKeysToPermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table
                ->foreign('role_id', 'permissions_role_id')
                ->references('role_id')
                ->on('roles')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
            $table->foreign('homepage_route_id', 'permissions_route_id')->references('route_id')->on('routes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('route_id', 'permission_routes_id')->references('route_id')->on('routes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('permissions_role_id');
            $table->dropForeign('permissions_route_id');
            $table->dropForeign('permission_routes_id');
        });
    }

}
